<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Obtener estado de entrega de una orden
     */
    public function show($orderId, Request $request)
    {
        $delivery = Delivery::where('order_id', $orderId)
            ->whereHas('orders', function ($query) use ($request) {
                $query->where('customer_id', $request->user()->id);
            })
            ->first();

        if (!$delivery) {
            return response()->json([
                'message' => 'Entrega no encontrada',
                'errors' => ['delivery' => ['No existe información de entrega para esta orden']],
            ], 404);
        }

        return response()->json([
            'data' => $delivery,
            'message' => 'Información de entrega',
        ], 200);
    }

    /**
     * Listar entregas en tránsito del cliente
     */
    public function index(Request $request)
    {
        $deliveries = Delivery::whereHas('orders', function ($query) use ($request) {
            $query->where('customer_id', $request->user()->id);
        })
        ->where('status', 'in_transit')
        ->orderBy('updated_at', 'desc')
        ->get();

        return response()->json([
            'data' => $deliveries,
            'message' => 'Entregas en tránsito',
        ], 200);
    }
}
