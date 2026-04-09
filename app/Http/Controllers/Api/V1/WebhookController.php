<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Handle Stripe webhook events
     * POST /api/v1/webhooks/stripe
     */
    public function handleStripeWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $sig_header = $request->header('Stripe-Signature');
            $endpoint_secret = config('services.stripe.webhook_secret');

            // Verificar la firma del webhook
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (SignatureVerificationException $e) {
            // Firma inválida
            \Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Invalid signature',
            ], 400);
        } catch (\UnexpectedValueException $e) {
            // Payload inválido
            \Log::warning('Invalid Stripe webhook payload', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Invalid payload',
            ], 400);
        }

        // Procesar diferentes tipos de eventos
        try {
            match ($event->type) {
                'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($event),
                'payment_intent.canceled' => $this->handlePaymentIntentCanceled($event),
                'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($event),
                'charge.refunded' => $this->handleChargeRefunded($event),
                default => $this->logUnhandledEvent($event),
            };

            // Responder que recibimos el webhook
            return response()->json(['received' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Error processing Stripe webhook', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);

            // Retornar 200 igual para no causar reintentos
            return response()->json(['error' => 'Processing failed'], 200);
        }
    }

    /**
     * Manejar evento: pago completado exitosamente
     */
    private function handlePaymentIntentSucceeded($event)
    {
        $paymentIntent = $event->data->object;

        // Buscar el Payment en BD usando stripe_payment_intent_id
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if (!$payment) {
            \Log::warning('Payment not found for webhook', [
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return;
        }

        // Extraer detalles del cargo
        $charge = $paymentIntent->charges->data[0] ?? null;

        // Actualizar Payment
        $payment->update([
            'status' => 'succeeded',
            'stripe_charge_id' => $charge?->id,
            'payment_method' => $charge?->payment_method_details?->type,
            'card_last_four' => $charge?->payment_method_details?->card?->last4,
            'card_brand' => $charge?->payment_method_details?->card?->brand,
            'stripe_response' => $paymentIntent->toArray(),
            'paid_at' => now(),
        ]);

        // Actualizar Order a Ready (indicando que fue pagada)
        $order = $payment->order;
        if ($order && in_array($order->status, ['Pending', 'Ready'])) {
            $order->update(['status' => 'Ready']);
        }

        \Log::info('Payment succeeded via webhook', [
            'payment_id' => $payment->id,
            'order_id' => $order->id ?? null,
            'stripe_charge_id' => $charge?->id,
        ]);
    }

    /**
     * Manejar evento: pago cancelado
     */
    private function handlePaymentIntentCanceled($event)
    {
        $paymentIntent = $event->data->object;

        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if (!$payment) {
            \Log::warning('Payment not found for cancellation webhook', [
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return;
        }

        $payment->update([
            'status' => 'canceled',
            'stripe_response' => $paymentIntent->toArray(),
        ]);

        \Log::info('Payment canceled via webhook', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
        ]);
    }

    /**
     * Manejar evento: pago fallido
     */
    private function handlePaymentIntentFailed($event)
    {
        $paymentIntent = $event->data->object;

        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if (!$payment) {
            \Log::warning('Payment not found for failure webhook', [
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return;
        }

        // Obtener mensaje de error si existe
        $lastError = $paymentIntent->last_payment_error;
        $errorMessage = $lastError?->message ?? 'Unknown error';

        $payment->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'stripe_response' => $paymentIntent->toArray(),
        ]);

        \Log::warning('Payment failed via webhook', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'error' => $errorMessage,
        ]);
    }

    /**
     * Manejar evento: reembolso procesado
     */
    private function handleChargeRefunded($event)
    {
        $charge = $event->data->object;

        // Buscar el Payment usando stripe_charge_id
        $payment = Payment::where('stripe_charge_id', $charge->id)
            ->first();

        if (!$payment) {
            \Log::warning('Payment not found for refund webhook', [
                'stripe_charge_id' => $charge->id,
            ]);

            return;
        }

        // Obtener monto reembolsado
        $refunded_amount = $charge->amount_refunded / 100; // Convertir de centavos

        $payment->update([
            'status' => 'canceled', // O podrías usar un status 'refunded' si lo prefieres
            'stripe_response' => $charge->toArray(),
        ]);

        \Log::info('Payment refunded via webhook', [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'refunded_amount' => $refunded_amount,
        ]);
    }

    /**
     * Registrar eventos no manejados
     */
    private function logUnhandledEvent($event)
    {
        \Log::info('Unhandled Stripe webhook event', [
            'event_type' => $event->type,
            'event_id' => $event->id,
        ]);
    }
}
