<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Services\OrderCalculationService;
use App\Services\OrderValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function __construct(
        private OrderCalculationService $calculationService,
        private OrderValidationService $validationService
    ) {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // 🔹 PREVIEW
    public function preview(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'coupon_id' => 'nullable',
            ]);

            $this->validationService->validateItems($validated['items']);

            $calculation = $this->calculationService->getOrderCalculation(
                $validated['items'],
                $validated['coupon_id'] ?? null
            );

            if (($validated['coupon_id'] ?? null) !== null) {
                $this->validationService->validateCoupon(
                    $validated['coupon_id'],
                    $calculation['subtotal']
                );
            }

            return response()->json([
                'success' => true,
                'data' => $calculation,
                'message' => 'Preview de orden generado',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'No se pudo generar preview',
                'errors' => ['order' => [$e->getMessage()]],
            ], 422);
        }
    }

    // 🔹 FUNCIÓN CENTRAL (SIN UNIT)
    private function calculateItemSubtotal(Product $product, $quantity): float
    {
        $price = $product->price;

        return match ($product->type) {
            'weight', 'volume' => ($quantity / 1000) * $price,
            default => $quantity * $price,
        };
    }

    // 🔹 STORE
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $validated = $request->validate([
                    'items' => 'required|array|min:1',
                    'items.*.product_id' => 'required|integer|exists:products,id',
                    'items.*.quantity' => 'required|numeric|min:1',
                    'coupon_id' => 'nullable',
                    'notes' => 'nullable|string|max:500',
                    'address' => 'nullable|string|max:255',
                    'destination_lat' => 'nullable|numeric',
                    'destination_lng' => 'nullable|numeric',
                ]);

                $customerId = $request->user()->id;

                $this->validationService->validate(
                    $validated['items'],
                    $validated['coupon_id'] ?? null
                );

                $calculation = $this->calculationService->getOrderCalculation(
                    $validated['items'],
                    $validated['coupon_id'] ?? null
                );

                $order = Order::create([
                    'customer_id' => $customerId,
                    'status' => 'Pending',
                    'type' => 'delivery',
                    'subtotal' => $calculation['subtotal'],
                    'discount' => $calculation['discount_amount'],
                    'delivery_fee' => $calculation['delivery_fee'],
                    'tax' => $calculation['tax'],
                    'total' => $calculation['total'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {

                    $product = Product::findOrFail($item['product_id']);

                    $subtotal = $this->calculateItemSubtotal(
                        $product,
                        $item['quantity']
                    );

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => round($subtotal, 2),
                    ]);
                }

                // 🔹 CUPÓN
                if (($validated['coupon_id'] ?? null) !== null) {

                    $couponReference = (string) $validated['coupon_id'];

                    $coupon = Coupon::query()
                        ->active()
                        ->when(
                            ctype_digit($couponReference),
                            fn($q) => $q->where('id', (int) $couponReference),
                            fn($q) => $q->where('code', $couponReference)
                        )
                        ->first();

                    if ($coupon) {
                        $coupon->customers()->attach($customerId, [
                            'order_id' => $order->id,
                            'used_at' => now(),
                        ]);

                        if (!is_null($coupon->used_count)) {
                            $coupon->increment('used_count');
                        }
                    }
                }

                // 🔹 DELIVERY
                Delivery::firstOrCreate(
                    ['order_id' => $order->id],
                    [
                        'user_id' => null,
                        'address' => $validated['address'] ?? 'Dirección pendiente',
                        'destination_lat' => $validated['destination_lat'] ?? null,
                        'destination_lng' => $validated['destination_lng'] ?? null,
                        'status' => 'pending',
                        'notes' => $validated['notes'] ?? null,
                        'total' => $order->total,
                    ]
                );

                // 🔹 STRIPE
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) ($order->total * 100),
                    'currency' => 'mxn',
                    'metadata' => [
                        'order_id' => $order->id,
                        'customer_id' => $customerId,
                    ],
                ]);

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'customer_id' => $customerId,
                    'amount' => $order->total,
                    'status' => 'pending',
                    'payment_method' => 'card',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'stripe_client_secret' => $paymentIntent->client_secret,
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $order->id,
                        'status' => $order->status,
                        'total' => $order->total,
                        'client_secret' => $paymentIntent->client_secret,
                    ],
                    'message' => 'Orden creada exitosamente',
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear la orden',
                'errors' => ['order' => [$e->getMessage()]],
            ], 422);
        }
    }

    // 🔹 REORDER (SIN UNIT)
    public function reorder($id, Request $request)
    {
        try {
            $originalOrder = Order::where('id', $id)
                ->where('customer_id', $request->user()->id)
                ->with('details')
                ->firstOrFail();

            $items = $originalOrder->details->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                ];
            })->values()->all();

            $this->validationService->validateItems($items);

            $calculation = $this->calculationService->getOrderCalculation($items);

            $newOrder = DB::transaction(function () use ($request, $items, $calculation) {

                $order = Order::create([
                    'customer_id' => $request->user()->id,
                    'status' => 'Pending',
                    'type' => 'delivery',
                    'subtotal' => $calculation['subtotal'],
                    'discount' => $calculation['discount_amount'],
                    'delivery_fee' => $calculation['delivery_fee'],
                    'tax' => $calculation['tax'],
                    'total' => $calculation['total'],
                ]);

                foreach ($items as $item) {

                    $product = Product::findOrFail($item['product_id']);

                    $subtotal = $this->calculateItemSubtotal(
                        $product,
                        $item['quantity']
                    );

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => round($subtotal, 2),
                    ]);
                }

                return $order;
            });

            return response()->json(['data' => $newOrder], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}