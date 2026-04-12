<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\DeliveryShowRequest;
use App\Http\Resources\Api\V1\DeliveryResource;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Obtener estado de entrega de una orden
     */
    public function show(DeliveryShowRequest $request, int $orderId)
    {
        $delivery = Delivery::where('order_id', $orderId)
            ->whereHas('orders', function ($query) use ($request) {
                $query->where('customer_id', $request->user()->id);
            })
            ->first();

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Entrega no encontrada',
                'errors' => ['delivery' => ['No existe información de entrega para esta orden']],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new DeliveryResource($delivery),
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
            'success' => true,
            'data' => DeliveryResource::collection($deliveries),
            'message' => 'Entregas en tránsito',
        ], 200);
    }

    /**
     * Tracking para cliente (ubicación repartidor + destino)
     */
    public function tracking(DeliveryShowRequest $request, int $orderId)
    {
        $delivery = Delivery::where('order_id', $orderId)
            ->whereHas('orders', function ($query) use ($request) {
                $query->where('customer_id', $request->user()->id);
            })
            ->first();

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Seguimiento no encontrado',
                'errors' => ['delivery' => ['No existe información de seguimiento para esta orden']],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $delivery->order_id,
                'status' => $delivery->status,
                'destination' => [
                    'address' => $delivery->address,
                    'lat' => $delivery->destination_lat,
                    'lng' => $delivery->destination_lng,
                ],
                'driver_location' => [
                    'lat' => $delivery->driver_lat,
                    'lng' => $delivery->driver_lng,
                    'updated_at' => $delivery->driver_location_updated_at,
                ],
            ],
            'message' => 'Seguimiento de entrega',
        ], 200);
    }
}
