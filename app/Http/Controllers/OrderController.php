<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderPreviewResource;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Delivery;
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
    private OrderCalculationService $calculationService;
    private OrderValidationService $validationService;

    public function __construct(
        OrderCalculationService $calculationService,
        OrderValidationService $validationService
    ) {
        $this->calculationService = $calculationService;
        $this->validationService = $validationService;
        $this->middleware('auth:api');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Preview order without creating it
     */
    public function preview(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1|max:99',
                'coupon_id' => 'nullable',
                'notes' => 'nullable|string|max:500',
            ]);
            
            // Validate items and get products
            $this->validationService->validateItems($validated['items']);
            
            // Calculate totals
            $calculation = $this->calculationService->getOrderCalculation(
                $validated['items'],
                $validated['coupon_id'] ?? null
            );

            // Validate coupon if present
            if ($validated['coupon_id'] ?? null) {
                $this->validationService->validateCoupon(
                    $validated['coupon_id'],
                    $calculation['subtotal']
                );
            }

            \Log::info('Order preview generated', [
                'customer_id' => auth()->id(),
                'items_count' => count($validated['items']),
                'total' => $calculation['total'],
            ]);

            return response()->json($calculation);

        } catch (\Exception $e) {
            \Log::error('Order preview error', [
                'error' => $e->getMessage(),
                'customer_id' => auth()->id(),
            ]);

            return response()->json(
                ['error' => $e->getMessage() ?? 'Error calculating order'],
                422
            );
        }
    }

    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        try {
            return \DB::transaction(function () use ($request) {
                $validated = $request->validate([
                    'items' => 'required|array|min:1',
                    'items.*.product_id' => 'required|integer|exists:products,id',
                    'items.*.quantity' => 'required|integer|min:1|max:99',
                    'coupon_id' => 'nullable',
                    'notes' => 'nullable|string|max:500',
                ]);
                $customerId = auth()->id();

                // Validate all inputs
                $this->validationService->validate(
                    $validated['items'],
                    $validated['coupon_id'] ?? null
                );

                // Calculate order totals
                $calculation = $this->calculationService->getOrderCalculation(
                    $validated['items'],
                    $validated['coupon_id'] ?? null
                );

                // Create Order
                $order = Order::create([
                    'customer_id' => $customerId,
                    'status' => 'pending',
                    'subtotal' => $calculation['subtotal'],
                    'discount' => $calculation['discount_amount'],
                    'delivery_fee' => $calculation['delivery_fee'],
                    'tax' => $calculation['tax'],
                    'total' => $calculation['total'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Create OrderDetails
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

                // Apply coupon if present
                if ($validated['coupon_id'] ?? null) {
                    $couponReference = (string) $validated['coupon_id'];

                    $coupon = Coupon::query()
                        ->active()
                        ->when(
                            ctype_digit($couponReference),
                            fn ($query) => $query->where('id', (int) $couponReference),
                            fn ($query) => $query->where('code', $couponReference)
                        )
                        ->first();

                    if ($coupon) {
                        // Link coupon usage to customer/order for history.
                        $coupon->customers()->attach($customerId, [
                            'order_id' => $order->id,
                            'used_at' => now(),
                        ]);

                        if (!is_null($coupon->used_count)) {
                            $coupon->increment('used_count');
                        }
                    }
                }

                // Create Payment Intent with Stripe
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int)($order->total * 100),
                    'currency' => 'usd',
                    'metadata' => [
                        'order_id' => $order->id,
                        'customer_id' => $customerId,
                    ],
                ]);

                // Create Payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'customer_id' => $customerId,
                    'amount' => $order->total,
                    'status' => 'pending',
                    'payment_method' => 'card',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'stripe_client_secret' => $paymentIntent->client_secret,
                ]);

                \Log::info('Order created successfully', [
                    'order_id' => $order->id,
                    'customer_id' => $customerId,
                    'total' => $order->total,
                    'payment_intent_id' => $paymentIntent->id,
                ]);

                return response()->json([
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
                    'items_count' => count($validated['items']),
                ], 201);

            });
        } catch (\Exception $e) {
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'customer_id' => auth()->id() ?? 'unknown',
            ]);

            return response()->json(
                ['error' => $e->getMessage() ?? 'Error creating order'],
                422
            );
        }
    }

    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        try {
            $query = Order::where('customer_id', auth()->id())->with('details.product');

            // Filtro por status
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            // Ordenamiento
            $query->orderBy('created_at', 'desc');

            // Paginación
            $orders = $query->paginate(20);

            return response()->json($orders);
        } catch (\Exception $e) {
            \Log::error('Orders listing error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error fetching orders'], 500);
        }
    }

    /**
     * Get order detail
     */
    public function show(string $id)
    {
        try {
            $order = Order::with('details.product', 'payments')
                ->where('id', $id)
                ->where('customer_id', auth()->id())
                ->firstOrFail();

            return response()->json($order);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Order detail error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error fetching order'], 500);
        }
    }

    /**
     * Cancel order
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('customer_id', auth()->id())
                ->firstOrFail();

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return response()->json(
                    ['error' => 'Order cannot be cancelled in current status'],
                    422
                );
            }

            $order->update(['status' => 'cancelled']);

            \Log::info('Order cancelled', [
                'order_id' => $order->id,
                'customer_id' => auth()->id(),
            ]);

            return response()->json(['message' => 'Order cancelled'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Order cancellation error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error cancelling order'], 500);
        }
    }
}
