<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerCoupon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Delivery;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Listar órdenes del cliente autenticado
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $status = $request->query('status');

        $query = Order::where('customer_id', $request->user()->id);

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->with(['details', 'deliveries'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $orders->items(),
            'meta' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ],
            'message' => 'Órdenes obtenidas',
        ], 200);
    }

    /**
     * Obtener la orden activa del cliente
     */
    public function active(Request $request)
    {
        $activeOrder = Order::where('customer_id', $request->user()->id)
            ->whereIn('status', ['Pending', 'Ready'])
            ->with(['details', 'deliveries'])
            ->latest('created_at')
            ->first();

        return response()->json([
            'data' => $activeOrder,
            'message' => $activeOrder ? 'Orden activa obtenida' : 'No hay orden activa',
        ], 200);
    }

    /**
     * Obtener detalles de una orden
     */
    public function show($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->with(['details', 'deliveries'])
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'errors' => ['order' => ['La orden solicitada no existe']],
            ], 404);
        }

        return response()->json([
            'data' => $order,
            'message' => 'Orden obtenida',
        ], 200);
    }

    /**
     * Crear nueva orden (checkout)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'coupon_id' => 'nullable|integer',
                'type' => 'required|in:in_store,delivery',
                'delivery_address' => 'required_if:type,delivery|string|max:255',
            ]);

            $customer = $request->user();
            $summary = $this->calculateSummary(
                $customer->id,
                $validated['items'],
                $validated['coupon_id'] ?? null
            );

            $order = DB::transaction(function () use ($validated, $customer, $summary) {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'subtotal' => $summary['subtotal'],
                    'total' => $summary['total'],
                    'discount' => $summary['discount_percent'],
                    'coupon_id' => $summary['coupon_id'],
                    'status' => 'Pending',
                    'type' => $validated['type'],
                ]);

                foreach ($summary['items'] as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                if ($validated['type'] === 'delivery') {
                    $defaultDriverId = User::query()->value('id');

                    if (!$defaultDriverId) {
                        throw ValidationException::withMessages([
                            'delivery' => ['No hay repartidor disponible para asignar la entrega'],
                        ]);
                    }

                    Delivery::create([
                        'order_id' => $order->id,
                        'user_id' => $defaultDriverId,
                        'address' => $validated['delivery_address'],
                        'status' => 'pending',
                        'total' => $summary['total'],
                    ]);
                }

                if ($summary['coupon']) {
                    $summary['coupon']->update(['status' => 0]);
                }

                return $order;
            });

            return response()->json([
                'data' => $order->load(['details', 'deliveries']),
                'message' => 'Orden creada exitosamente',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Previsualizar una orden antes del checkout
     */
    public function preview(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'coupon_id' => 'nullable|integer',
            ]);

            $summary = $this->calculateSummary(
                $request->user()->id,
                $validated['items'],
                $validated['coupon_id'] ?? null
            );

            return response()->json([
                'data' => [
                    'items' => $summary['items'],
                    'subtotal' => $summary['subtotal'],
                    'discount_percent' => $summary['discount_percent'],
                    'discount_amount' => $summary['discount_amount'],
                    'total' => $summary['total'],
                    'coupon_id' => $summary['coupon_id'],
                ],
                'message' => 'Resumen de orden obtenido',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Repetir una orden anterior
     */
    public function reorder($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'sometimes|in:in_store,delivery',
                'delivery_address' => 'required_if:type,delivery|string|max:255',
                'coupon_id' => 'nullable|integer',
            ]);

            $originalOrder = Order::where('id', $id)
                ->where('customer_id', $request->user()->id)
                ->with(['details', 'deliveries'])
                ->first();

            if (!$originalOrder) {
                return response()->json([
                    'message' => 'Orden no encontrada',
                    'errors' => ['order' => ['La orden solicitada no existe']],
                ], 404);
            }

            $items = $originalOrder->details->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                ];
            })->values()->all();

            $summary = $this->calculateSummary(
                $request->user()->id,
                $items,
                $validated['coupon_id'] ?? null
            );

            $type = $validated['type'] ?? $originalOrder->type;
            $deliveryAddress = $validated['delivery_address']
                ?? optional($originalOrder->deliveries->first())->address
                ?? $request->user()->address;

            if ($type === 'delivery' && !$deliveryAddress) {
                return response()->json([
                    'message' => 'Dirección requerida para entrega',
                    'errors' => ['delivery_address' => ['Debes enviar una dirección para pedidos a domicilio']],
                ], 422);
            }

            $newOrder = DB::transaction(function () use ($request, $summary, $type, $deliveryAddress) {
                $order = Order::create([
                    'customer_id' => $request->user()->id,
                    'subtotal' => $summary['subtotal'],
                    'total' => $summary['total'],
                    'discount' => $summary['discount_percent'],
                    'coupon_id' => $summary['coupon_id'],
                    'status' => 'Pending',
                    'type' => $type,
                ]);

                foreach ($summary['items'] as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                if ($type === 'delivery') {
                    $defaultDriverId = User::query()->value('id');

                    if (!$defaultDriverId) {
                        throw ValidationException::withMessages([
                            'delivery' => ['No hay repartidor disponible para asignar la entrega'],
                        ]);
                    }

                    Delivery::create([
                        'order_id' => $order->id,
                        'user_id' => $defaultDriverId,
                        'address' => $deliveryAddress,
                        'status' => 'pending',
                        'total' => $summary['total'],
                    ]);
                }

                if ($summary['coupon']) {
                    $summary['coupon']->update(['status' => 0]);
                }

                return $order;
            });

            return response()->json([
                'data' => $newOrder->load(['details', 'deliveries']),
                'message' => 'Orden repetida exitosamente',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Cancelar una orden
     */
    public function destroy($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada',
                'errors' => ['order' => ['La orden no existe']],
            ], 404);
        }

        if (!in_array($order->status, ['Pending', 'Ready'])) {
            return response()->json([
                'message' => 'No se puede cancelar esta orden',
                'errors' => ['status' => ["No se pueden cancelar órdenes con estado {$order->status}"]],
            ], 409);
        }

        $order->update(['status' => 'Canceled']);

        return response()->json([
            'data' => $order,
            'message' => 'Orden cancelada',
        ], 200);
    }

    private function calculateSummary(int $customerId, array $payloadItems, ?int $couponId): array
    {
        $items = [];
        $subtotal = 0;

        foreach ($payloadItems as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                throw ValidationException::withMessages([
                    'items' => ["El producto {$item['product_id']} no existe"],
                ]);
            }

            if ($product->stock < $item['quantity']) {
                throw ValidationException::withMessages([
                    'stock' => ["El producto {$product->name} no tiene suficiente stock"],
                ]);
            }

            $lineSubtotal = (float) $product->price * (float) $item['quantity'];
            $subtotal += $lineSubtotal;

            $items[] = [
                'product_id' => (int) $product->id,
                'name' => $product->name,
                'quantity' => (int) $item['quantity'],
                'unit_price' => (float) $product->price,
                'subtotal' => (float) $lineSubtotal,
            ];
        }

        $coupon = null;
        $discountPercent = 0;
        $couponReferenceId = null;

        if ($couponId) {
            $coupon = CustomerCoupon::where('id', $couponId)
                ->where('customer_id', $customerId)
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                throw ValidationException::withMessages([
                    'coupon_id' => ['Cupón inválido, vencido o no disponible para este cliente'],
                ]);
            }

            $discountPercent = (int) $coupon->discount;
            $couponReferenceId = $coupon->id;
        }

        $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
        $total = max(round($subtotal - $discountAmount, 2), 0);

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'coupon' => $coupon,
            'coupon_id' => $couponReferenceId,
        ];
    }
}
