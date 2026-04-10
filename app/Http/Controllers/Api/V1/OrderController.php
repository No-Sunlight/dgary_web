<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Services\OrderCalculationService;
use App\Services\OrderValidationService;
use Illuminate\Http\Request;
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

    public function preview(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1|max:99',
                'coupon_id' => 'nullable|string|exists:coupons,code',
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

    public function store(Request $request)
    {
        try {
            return \DB::transaction(function () use ($request) {
                $validated = $request->validate([
                    'items' => 'required|array|min:1',
                    'items.*.product_id' => 'required|integer|exists:products,id',
                    'items.*.quantity' => 'required|integer|min:1|max:99',
                    'coupon_id' => 'nullable|string|exists:coupons,code',
                    'notes' => 'nullable|string|max:500',
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
                    'subtotal' => $calculation['subtotal'],
                    'discount' => $calculation['discount_amount'],
                    'delivery_fee' => $calculation['delivery_fee'],
                    'tax' => $calculation['tax'],
                    'total' => $calculation['total'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                    ]);
                }

                if (($validated['coupon_id'] ?? null) !== null) {
                    $coupon = Coupon::where('code', $validated['coupon_id'])->first();
                    if ($coupon) {
                        $coupon->customers()->attach($customerId, [
                            'order_id' => $order->id,
                            'used_at' => now(),
                        ]);
                    }
                }

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
                    'subtotal' => $order->subtotal,
                    'discount' => $order->discount,
                    'delivery_fee' => $order->delivery_fee,
                    'tax' => $order->tax,
                    'total' => $order->total,
                    'payment_id' => $payment->id,
                    'payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                    ],
                    'message' => 'Orden creada exitosamente',
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'No se pudo crear la orden',
                'errors' => ['order' => [$e->getMessage()]],
            ], 422);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Order::where('customer_id', $request->user()->id)
                ->with('details.product');

            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $orders->items(),
                'meta' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                ],
                'message' => 'Órdenes obtenidas',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error fetching orders',
                'errors' => ['order' => [$e->getMessage()]],
            ], 500);
        }
    }

    public function active(Request $request)
    {
        try {
            $order = Order::where('customer_id', $request->user()->id)
                ->whereIn('status', ['Pending', 'Ready'])
                ->with('details.product', 'payments')
                ->latest('created_at')
                ->first();

            return response()->json(['data' => $order], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching active order'], 500);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $order = Order::with('details.product', 'payments')
                ->where('id', $id)
                ->where('customer_id', $request->user()->id)
                ->firstOrFail();

            return response()->json(['data' => $order], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

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

            $newOrder = \DB::transaction(function () use ($request, $items, $calculation) {
                $order = Order::create([
                    'customer_id' => $request->user()->id,
                    'status' => 'Pending',
                    'subtotal' => $calculation['subtotal'],
                    'discount' => $calculation['discount_amount'],
                    'delivery_fee' => $calculation['delivery_fee'],
                    'tax' => $calculation['tax'],
                    'total' => $calculation['total'],
                ]);

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                    ]);
                }

                return $order;
            });

            return response()->json(['data' => $newOrder], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $order = Order::where('id', $id)
                ->where('customer_id', $request->user()->id)
                ->firstOrFail();

            if (!in_array(strtolower((string) $order->status), ['pending', 'ready'], true)) {
                return response()->json(['error' => 'Cannot cancel order in current status'], 422);
            }

            $order->update(['status' => 'Canceled']);

            return response()->json(['data' => $order], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error cancelling order'], 422);
        }
    }
}
