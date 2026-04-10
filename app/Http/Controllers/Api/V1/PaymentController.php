<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Stripe\Stripe;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    private function getOrCreateStripeCustomerId($customer): string
    {
        if (!empty($customer->stripe_customer_id)) {
            return $customer->stripe_customer_id;
        }

        $stripeCustomer = StripeCustomer::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'metadata' => [
                'app_customer_id' => (string) $customer->id,
            ],
        ]);

        $customer->stripe_customer_id = $stripeCustomer->id;
        $customer->save();

        return $stripeCustomer->id;
    }

    private function extractStripePaymentMethodId(?array $stripeResponse): ?string
    {
        if (!$stripeResponse) {
            return null;
        }

        $fromRoot = $stripeResponse['payment_method'] ?? null;
        if (is_string($fromRoot) && $fromRoot !== '') {
            return $fromRoot;
        }

        $fromCharge = $stripeResponse['charges']['data'][0]['payment_method'] ?? null;
        if (is_string($fromCharge) && $fromCharge !== '') {
            return $fromCharge;
        }

        return null;
    }

    public function __construct()
    {
        // Inicializar Stripe con clave secreta
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear setup intent para agregar tarjeta a billetera (sin cobrar)
     * Usado internamente por createPaymentIntent cuando orderId == "wallet-add-card"
     */
    private function createWalletCardIntent(Request $request)
    {
        try {
            $stripeCustomerId = $this->getOrCreateStripeCustomerId($request->user());

            // Crear SetupIntent (NO cobra nada, solo guarda la tarjeta)
            $setupIntent = SetupIntent::create([
                'payment_method_types' => ['card'],
                'customer' => $stripeCustomerId,
                'metadata' => [
                    'operation' => 'add_card_to_wallet',
                    'customer_id' => $request->user()->id,
                    'customer_name' => $request->user()->name,
                ],
                'description' => "Agregar tarjeta a billetera - " . $request->user()->name,
            ]);

            // Guardar en BD con order_id=NULL (para billetera)
            $payment = Payment::create([
                'order_id' => null,
                'customer_id' => $request->user()->id,
                'stripe_payment_intent_id' => $setupIntent->id, // Guardamos setup intent ID
                'amount' => 0, // Sin cargo
                'currency' => 'MXN',
                'status' => 'pending',
                'stripe_response' => $setupIntent->toArray(),
            ]);

            return response()->json([
                'data' => [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $setupIntent->id,
                    'client_secret' => $setupIntent->client_secret,
                    'amount' => 0,
                    'currency' => 'mxn',
                    'is_setup_intent' => true, // Indicador para el cliente
                ],
                'message' => 'Setup intent creado para agregar tarjeta (sin cobro)',
            ], 201);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'message' => 'Error de Stripe',
                'errors' => ['stripe' => [$e->getMessage()]],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear setup intent para billetera',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Crear un payment intent para una orden
     * POST /api/v1/orders/{id}/payment/intent
     * 
     * Casos especiales:
     * - orderId = "wallet-add-card": crear intent para agregar tarjeta a billetera (sin orden)
     */
    public function createPaymentIntent($orderId, Request $request)
    {
        try {
            // Caso especial: agregar tarjeta a billetera
            if ($orderId === 'wallet-add-card') {
                return $this->createWalletCardIntent($request);
            }

            // Caso normal: payment intent para una orden
            $order = Order::where('id', $orderId)
                ->where('customer_id', $request->user()->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Orden no encontrada',
                    'errors' => ['order' => ['La orden solicitada no existe']],
                ], 404);
            }

            // Validar que la orden esté lista para pago (compatibilidad case-insensitive)
            $normalizedOrderStatus = strtolower((string) $order->status);
            if (!in_array($normalizedOrderStatus, ['pending', 'ready'], true)) {
                return response()->json([
                    'message' => 'La orden no puede ser pagada en este momento',
                    'errors' => ['status' => ['Estado de orden inválido para pago']],
                ], 409);
            }

            $stripeCustomerId = $this->getOrCreateStripeCustomerId($request->user());

            // Verificar si ya existe un payment intent pendiente
            $existingPayment = Payment::where('order_id', $orderId)
                ->where('status', 'pending')
                ->first();

            if ($existingPayment && $existingPayment->stripe_payment_intent_id) {
                // Recuperar intent existente
                try {
                    $intent = PaymentIntent::retrieve($existingPayment->stripe_payment_intent_id);
                    $expectedAmount = (int) ($order->total * 100);
                    $intentCurrency = strtolower((string) $intent->currency);

                    if ((int) $intent->amount !== $expectedAmount || $intentCurrency !== 'mxn') {
                        $existingPayment->delete();
                    } else {
                        return response()->json([
                            'data' => [
                                'payment_id' => $existingPayment->id,
                                'stripe_payment_intent_id' => $existingPayment->stripe_payment_intent_id,
                                'client_secret' => $intent->client_secret,
                                'amount' => $expectedAmount,
                                'currency' => 'mxn',
                            ],
                            'message' => 'Payment intent recuperado',
                        ], 200);
                    }
                } catch (\Exception $e) {
                    // Si expiró o no existe, crear uno nuevo
                    $existingPayment->delete();
                }
            }

            // Crear nuevo payment intent en Stripe
            $intent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100), // Convertir a centavos
                'currency' => 'mxn',
                'customer' => $stripeCustomerId,
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_id' => $request->user()->id,
                    'customer_name' => $request->user()->name,
                    'order_items' => $order->details()->count(),
                ],
                'description' => "Pago Orden #" . $order->id . " - " . $request->user()->name,
            ]);

            // Guardar payment en BD
            $payment = Payment::create([
                'order_id' => $order->id,
                'customer_id' => $request->user()->id,
                'stripe_payment_intent_id' => $intent->id,
                'amount' => $order->total,
                'currency' => 'MXN',
                'status' => 'pending',
                'stripe_response' => $intent->toArray(),
            ]);

            return response()->json([
                'data' => [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $intent->id,
                    'client_secret' => $intent->client_secret,
                    'amount' => (int) ($order->total * 100),
                    'currency' => 'mxn',
                ],
                'message' => 'Payment intent creado exitosamente',
            ], 201);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'message' => 'Error de Stripe',
                'errors' => ['stripe' => [$e->getMessage()]],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear payment intent',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Confirmar adición de tarjeta a billetera (SetupIntent)
     * NO cobra nada, solo guarda la tarjeta
     */
    private function confirmWalletCard(Request $request, $setupIntentId)
    {
        try {
            // Obtener payment de BD (order_id es NULL para billetera)
            $payment = Payment::where('stripe_payment_intent_id', $setupIntentId)
                ->where('customer_id', $request->user()->id)
                ->whereNull('order_id')
                ->first();

            if (!$payment) {
                return response()->json([
                    'message' => 'Pago no encontrado',
                    'errors' => ['payment' => ['No hay pago asociado para la billetera']],
                ], 404);
            }

            // Verificar estado en Stripe
            try {
                $setupIntent = SetupIntent::retrieve($setupIntentId);
                \Log::info("SetupIntent retrieved: {$setupIntentId}, status: {$setupIntent->status}");

                if ($setupIntent->status === 'succeeded') {
                    // Tarjeta agregada exitosamente sin cobro
                    // SetupIntent crea un PaymentMethod que se puede reutilizar
                    // payment_method puede ser un string (ID) o un objeto, necesitamos recuperarlo
                    $paymentMethodId = is_string($setupIntent->payment_method) 
                        ? $setupIntent->payment_method 
                        : $setupIntent->payment_method->id;

                    \Log::info("PaymentMethod ID: {$paymentMethodId}, type: " . gettype($setupIntent->payment_method));

                    // Obtener detalles del PaymentMethod desde Stripe
                    $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
                    \Log::info("PaymentMethod retrieved: type={$paymentMethod->type}");

                    // Obtener detalles de la tarjeta
                    if ($paymentMethod && $paymentMethod->type === 'card') {
                        $card = $paymentMethod->card;
                        
                        $payment->update([
                            'status' => 'succeeded',
                            'stripe_charge_id' => null, // Sin cargo en SetupIntent
                            'payment_method' => 'card',
                            'card_last_four' => $card->last4,
                            'card_brand' => ucfirst($card->brand),
                            'stripe_response' => $setupIntent->toArray(),
                            'paid_at' => now(),
                        ]);

                        return response()->json([
                            'data' => [
                                'payment_id' => $payment->id,
                                'status' => 'succeeded',
                                'card_brand' => ucfirst($card->brand),
                                'card_last_four' => $card->last4,
                                'message' => 'Tarjeta agregada sin cobro ✓',
                            ],
                            'message' => 'Tarjeta guardada en billetera exitosamente',
                        ], 200);
                    } else {
                        $payment->update([
                            'status' => 'failed',
                            'stripe_response' => $setupIntent->toArray(),
                        ]);

                        return response()->json([
                            'message' => 'Tipo de método de pago no soportado',
                            'errors' => ['payment' => ['Solo se aceptan tarjetas de crédito/débito']],
                        ], 402);
                    }
                } elseif ($setupIntent->status === 'processing') {
                    $payment->update(['status' => 'processing']);

                    return response()->json([
                        'data' => [
                            'payment_id' => $payment->id,
                            'status' => 'processing',
                        ],
                        'message' => 'Guardando tarjeta. Por favor espera.',
                    ], 202);
                } else {
                    $payment->update([
                        'status' => 'failed',
                        'stripe_response' => $setupIntent->toArray(),
                    ]);

                    return response()->json([
                        'message' => 'Falló guardar tarjeta',
                        'errors' => ['payment' => ["Estado: {$setupIntent->status}"]],
                    ], 402);
                }
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $payment->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                return response()->json([
                    'message' => 'Error verificando tarjeta',
                    'errors' => ['stripe' => [$e->getMessage()]],
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error guardando tarjeta en billetera',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Confirmar pago y marcar orden como pagada
     * POST /api/v1/orders/{id}/payment/confirm
     * 
     * Casos especiales:
     * - orderId = "wallet-add-card": confirmar adición de tarjeta a billetera
     */
    public function confirmPayment($orderId, Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_intent_id' => 'required|string',
            ]);

            // Caso especial: confirmar tarjeta agregada a billetera
            if ($orderId === 'wallet-add-card') {
                return $this->confirmWalletCard($request, $validated['payment_intent_id']);
            }

            // Caso normal: confirmar pago de orden
            $order = Order::where('id', $orderId)
                ->where('customer_id', $request->user()->id)
                ->with(['details'])
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Orden no encontrada',
                    'errors' => ['order' => ['La orden solicitada no existe']],
                ], 404);
            }

            // Obtener payment de BD
            $payment = Payment::where('order_id', $orderId)
                ->where('stripe_payment_intent_id', $validated['payment_intent_id'])
                ->first();

            if (!$payment) {
                return response()->json([
                    'message' => 'Pago no encontrado',
                    'errors' => ['payment' => ['No hay pago asociado a esta orden']],
                ], 404);
            }

            // Verificar estado en Stripe
            try {
                $intent = PaymentIntent::retrieve($validated['payment_intent_id']);

                if ($intent->status === 'succeeded') {
                    // Pago exitoso
                    $charge = $intent->charges->data[0] ?? null;

                    $payment->update([
                        'status' => 'succeeded',
                        'stripe_charge_id' => $charge?->id,
                        'payment_method' => $charge?->payment_method_details?->type,
                        'card_last_four' => $charge?->payment_method_details?->card?->last4,
                        'card_brand' => $charge?->payment_method_details?->card?->brand,
                        'stripe_response' => $intent->toArray(),
                        'paid_at' => now(),
                    ]);

                    // Actualizar estado de orden
                    $order->update([
                        'status' => 'Ready',
                    ]);

                    return response()->json([
                        'data' => [
                            'payment_id' => $payment->id,
                            'status' => 'succeeded',
                            'order_status' => $order->status,
                            'amount_paid' => $payment->amount,
                        ],
                        'message' => 'Pago confirmado exitosamente',
                    ], 200);
                } elseif ($intent->status === 'processing') {
                    $payment->update(['status' => 'processing']);

                    return response()->json([
                        'data' => [
                            'payment_id' => $payment->id,
                            'status' => 'processing',
                        ],
                        'message' => 'Pago en proceso. Por favor espera.',
                    ], 202);
                } elseif ($intent->status === 'requires_payment_method') {
                    $payment->update([
                        'status' => 'failed',
                        'error_message' => 'Se requiere método de pago',
                        'stripe_response' => $intent->toArray(),
                    ]);

                    return response()->json([
                        'message' => 'Pago fallido',
                        'errors' => ['payment' => ['El pago requiere un método de pago válido']],
                    ], 402);
                } else {
                    $payment->update([
                        'status' => 'failed',
                        'stripe_response' => $intent->toArray(),
                    ]);

                    return response()->json([
                        'message' => 'Pago no completado',
                        'errors' => ['payment' => ["Estado del pago: {$intent->status}"]],
                    ], 402);
                }
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $payment->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                return response()->json([
                    'message' => 'Error al verificar pago',
                    'errors' => ['stripe' => [$e->getMessage()]],
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al confirmar pago',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Cobrar una orden con una tarjeta ya guardada en Stripe
     * POST /api/v1/orders/{id}/payment/use-saved
     */
    public function payWithSavedCard($orderId, Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_method_id' => 'required|string',
            ]);

            $order = Order::where('id', $orderId)
                ->where('customer_id', $request->user()->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Orden no encontrada',
                    'errors' => ['order' => ['La orden solicitada no existe']],
                ], 404);
            }

            $normalizedOrderStatus = strtolower((string) $order->status);
            if (!in_array($normalizedOrderStatus, ['pending', 'ready'], true)) {
                return response()->json([
                    'message' => 'La orden no puede ser pagada en este momento',
                    'errors' => ['status' => ['Estado de orden inválido para pago']],
                ], 409);
            }

            $stripeCustomerId = $this->getOrCreateStripeCustomerId($request->user());

            $stripePaymentMethod = PaymentMethod::retrieve($validated['payment_method_id']);
            $paymentMethodCustomer = null;
            if (is_string($stripePaymentMethod->customer)) {
                $paymentMethodCustomer = $stripePaymentMethod->customer;
            } elseif (is_object($stripePaymentMethod->customer) && isset($stripePaymentMethod->customer->id)) {
                $paymentMethodCustomer = $stripePaymentMethod->customer->id;
            }

            if (empty($paymentMethodCustomer)) {
                try {
                    $stripePaymentMethod->attach(['customer' => $stripeCustomerId]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json([
                        'message' => 'La tarjeta guardada no puede reutilizarse automáticamente',
                        'errors' => ['payment' => ['Tarjeta legacy no adjuntable. Usa "Pagar con nueva tarjeta" para volver a guardarla.']],
                    ], 409);
                }
            } elseif ($paymentMethodCustomer !== $stripeCustomerId) {
                return response()->json([
                    'message' => 'La tarjeta no pertenece al cliente actual',
                    'errors' => ['payment' => ['La tarjeta seleccionada no está vinculada a tu cuenta']],
                ], 409);
            }

            $intent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100),
                'currency' => 'mxn',
                'customer' => $stripeCustomerId,
                'payment_method' => $validated['payment_method_id'],
                'confirm' => true,
                'off_session' => true,
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_id' => $request->user()->id,
                    'customer_name' => $request->user()->name,
                    'flow' => 'saved_card',
                ],
                'description' => "Pago Orden #{$order->id} - tarjeta guardada",
            ]);

            $charge = $intent->charges->data[0] ?? null;

            $payment = Payment::create([
                'order_id' => $order->id,
                'customer_id' => $request->user()->id,
                'stripe_payment_intent_id' => $intent->id,
                'stripe_charge_id' => $charge?->id,
                'amount' => $order->total,
                'currency' => 'MXN',
                'status' => $intent->status === 'succeeded' ? 'succeeded' : ($intent->status === 'processing' ? 'processing' : 'failed'),
                'payment_method' => $charge?->payment_method_details?->type ?? 'card',
                'card_last_four' => $charge?->payment_method_details?->card?->last4,
                'card_brand' => $charge?->payment_method_details?->card?->brand,
                'stripe_response' => $intent->toArray(),
                'paid_at' => $intent->status === 'succeeded' ? now() : null,
                'error_message' => $intent->last_payment_error?->message,
            ]);

            if ($intent->status === 'succeeded') {
                $order->update(['status' => 'Ready']);

                return response()->json([
                    'data' => [
                        'payment_id' => $payment->id,
                        'status' => 'succeeded',
                        'order_status' => $order->status,
                        'amount_paid' => $payment->amount,
                    ],
                    'message' => 'Pago exitoso con tarjeta guardada',
                ], 200);
            }

            if ($intent->status === 'processing') {
                return response()->json([
                    'data' => [
                        'payment_id' => $payment->id,
                        'status' => 'processing',
                        'order_status' => $order->status,
                        'amount_paid' => 0,
                    ],
                    'message' => 'Pago en proceso',
                ], 202);
            }

            return response()->json([
                'message' => 'No se pudo cobrar con esta tarjeta guardada',
                'errors' => ['payment' => [$intent->last_payment_error?->message ?? "Estado: {$intent->status}"]],
            ], 402);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'message' => 'Error de Stripe',
                'errors' => ['stripe' => [$e->getMessage()]],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cobrar tarjeta guardada',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Obtener estado del pago de una orden
     * GET /api/v1/orders/{id}/payment/status
     */
    public function getPaymentStatus($orderId, Request $request)
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'errors' => ['order' => ['La orden solicitada no existe']],
            ], 404);
        }

        $payment = Payment::where('order_id', $orderId)
            ->latest()
            ->first();

        return response()->json([
            'data' => [
                'order_id' => $order->id,
                'payment_id' => $payment?->id,
                'payment_status' => $payment?->status ?? 'not_initiated',
                'amount' => $payment?->amount ?? $order->total,
                'order_status' => $order->status,
                'paid_at' => $payment?->paid_at,
            ],
            'message' => 'Estado de pago obtenido',
        ], 200);
    }

    /**
     * Reembolsar un pago
     * POST /api/v1/orders/{id}/payment/refund
     */
    public function refundPayment($orderId, Request $request)
    {
        try {
            $order = Order::where('id', $orderId)
                ->where('customer_id', $request->user()->id)
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Orden no encontrada',
                    'errors' => ['order' => ['La orden solicitada no existe']],
                ], 404);
            }

            $payment = Payment::where('order_id', $orderId)
                ->where('status', 'succeeded')
                ->first();

            if (!$payment) {
                return response()->json([
                    'message' => 'No hay pago para reembolsar',
                    'errors' => ['payment' => ['Solo se pueden reembolsar pagos exitosos']],
                ], 409);
            }

            if (!$payment->stripe_charge_id) {
                return response()->json([
                    'message' => 'No se puede reembolsar este pago',
                    'errors' => ['payment' => ['Información de cargre incompleta']],
                ], 409);
            }

            // Crear reembolso en Stripe
            $refund = \Stripe\Refund::create([
                'charge' => $payment->stripe_charge_id,
                'metadata' => [
                    'order_id' => $order->id,
                    'reason' => 'customer_request',
                ],
            ]);

            $payment->update([
                'status' => 'canceled',
                'stripe_response' => $refund->toArray(),
            ]);

            return response()->json([
                'data' => [
                    'refund_id' => $refund->id,
                    'amount_refunded' => $payment->amount,
                    'status' => 'refunded',
                ],
                'message' => 'Pago reembolsado exitosamente',
            ], 200);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'message' => 'Error al reembolsar',
                'errors' => ['stripe' => [$e->getMessage()]],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar reembolso',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Obtener todas las tarjetas guardadas (historial de pagos exitosos)
     * GET /api/v1/payment-methods
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            $customerId = $request->user()->id;

            // Obtener todos los pagos exitosos del usuario
            // Filtrar: status=succeeded, con datos de tarjeta, puede ser sin orden (billetera)
            $payments = Payment::where('customer_id', $customerId)
                ->where('status', 'succeeded')
                ->whereNotNull('card_last_four')
                ->whereNotNull('card_brand')
                ->orderByDesc('paid_at')
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'No hay tarjetas guardadas',
                ], 200);
            }

            // Agrupar tarjetas únicas (por últimos 4 dígitos + marca)
            $uniqueCards = [];
            $cardMap = [];

            foreach ($payments as $payment) {
                $paymentMethodId = $this->extractStripePaymentMethodId($payment->stripe_response);
                if (empty($paymentMethodId)) {
                    continue;
                }

                $cardKey = $payment->card_brand . '-' . $payment->card_last_four;

                if (!isset($cardMap[$cardKey])) {
                    $cardMap[$cardKey] = [
                        'payment_id' => $payment->id,
                        'stripe_charge_id' => $payment->stripe_charge_id,
                        'stripe_payment_method_id' => $paymentMethodId,
                        'card_brand' => ucfirst($payment->card_brand ?? 'Unknown'),
                        'card_last_four' => $payment->card_last_four,
                        'card_display' => ucfirst($payment->card_brand ?? 'Card') . ' •••• ' . $payment->card_last_four,
                        'first_used' => $payment->paid_at,
                        'last_used' => $payment->paid_at,
                        'usage_count' => 1,
                    ];
                    $uniqueCards[] = &$cardMap[$cardKey];
                } else {
                    $cardMap[$cardKey]['usage_count']++;
                    $cardMap[$cardKey]['last_used'] = max(
                        $cardMap[$cardKey]['last_used'],
                        $payment->paid_at
                    );
                    if (empty($cardMap[$cardKey]['stripe_payment_method_id']) && !empty($paymentMethodId)) {
                        $cardMap[$cardKey]['stripe_payment_method_id'] = $paymentMethodId;
                    }
                }
            }

            // Ordenar por último uso (descendente)
            usort($uniqueCards, function ($a, $b) {
                return $b['last_used'] <=> $a['last_used'];
            });

            return response()->json([
                'data' => $uniqueCards,
                'message' => 'Tarjetas obtenidas exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener tarjetas',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Limpiar tarjetas legacy no reutilizables del usuario autenticado
     * POST /api/v1/payment-methods/cleanup
     */
    public function cleanupLegacyPaymentMethods(Request $request)
    {
        try {
            $customerId = $request->user()->id;

            $payments = Payment::where('customer_id', $customerId)
                ->where('status', 'succeeded')
                ->whereNull('order_id')
                ->whereNotNull('card_last_four')
                ->whereNotNull('card_brand')
                ->get();

            $deleted = 0;
            foreach ($payments as $payment) {
                $paymentMethodId = $this->extractStripePaymentMethodId($payment->stripe_response);
                if (empty($paymentMethodId)) {
                    $payment->delete();
                    $deleted++;
                }
            }

            return response()->json([
                'data' => [
                    'deleted' => $deleted,
                ],
                'message' => 'Limpieza de tarjetas legacy completada',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al limpiar tarjetas legacy',
                'errors' => ['payment' => [$e->getMessage()]],
            ], 500);
        }
    }
}
