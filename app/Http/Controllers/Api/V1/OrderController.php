<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Delivery;
use App\Models\Product;
use Illuminate\Http\Request;
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

        $orders = $query->orderBy('created_at', 'desc')
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
                'coupon_id' => 'nullable|integer|exists:customer_coupons,id',
                'type' => 'required|in:in_store,delivery',
                'delivery_address' => 'required_if:type,delivery|string',
            ]);

            $customer = $request->user();
            $subtotal = 0;
            $discount = 0;

            // Calcular subtotal y validar stock
            $items = [];
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => 'Stock insuficiente',
                        'errors' => ['stock' => ["El producto {$product->name} no tiene suficiente stock"]],
                    ], 409);
                }
                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;
                $items[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemTotal,
                ];
            }

            // Aplicar cupón si existe
            if (isset($validated['coupon_id'])) {
                $coupon = \App\Models\CustomerCoupon::find($validated['coupon_id']);
                if ($coupon) {
                    $discount = $coupon->discount;
                }
            }

            $total = $subtotal - ($subtotal * $discount / 100);

            // Crear orden
            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'total' => $total,
                'discount' => $discount,
                'coupon_id' => $validated['coupon_id'] ?? null,
                'status' => 'Pending',
                'type' => $validated['type'],
            ]);

            // Crear detalles de orden
            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Si es entrega, crear registro
            if ($validated['type'] === 'delivery') {
                Delivery::create([
                    'order_id' => $order->id,
                    'user_id' => null,
                    'address' => $validated['delivery_address'],
                    'status' => 'pending',
                    'total' => $total,
                ]);
            }

            return response()->json([
                'data' => $order->load('details'),
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
}
