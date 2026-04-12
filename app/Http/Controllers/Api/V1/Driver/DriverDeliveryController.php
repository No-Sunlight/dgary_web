<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Driver\UpdateDeliveryLocationRequest;
use App\Http\Requests\Api\V1\Driver\UpdateDeliveryStatusRequest;
use App\Http\Resources\Api\V1\Driver\DriverDeliveryResource;
use App\Http\Resources\Api\V1\Driver\DeliveryDetailResource;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DriverDeliveryController extends Controller
{
    /**
     * Listar entregas asignadas al driver (activas)
     * 
     * @route GET /api/v1/driver/deliveries
     * @query status=pending,ready,in_transit (filtro opcional)
     */
    public function index(Request $request)
    {
        try {
            $driver = $request->user();
            
            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $query = Delivery::where('user_id', $driver->id)
                ->with(['order', 'order.customer', 'order.details', 'order.details.product']);

            // Filtrar por estado si se proporciona
            if ($request->has('status')) {
                $statuses = explode(',', $request->query('status'));
                $query->whereIn('status', $statuses);
            } else {
                // Por defecto, mostrar entregas activas
                $query->whereIn('status', ['pending', 'ready', 'in_transit']);
            }

            // Ordenar por fecha de actualización descendente
            $deliveries = $query->orderBy('updated_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => DriverDeliveryResource::collection($deliveries),
                'message' => 'Entregas activas',
                'count' => $deliveries->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener entregas',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Listar entregas completadas/canceladas (histórico)
     * 
     * @route GET /api/v1/driver/deliveries/completed
     */
    public function completed(Request $request)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $deliveries = Delivery::where('user_id', $driver->id)
                ->whereIn('status', ['completed', 'canceled', 'refund'])
                ->with(['order', 'order.customer', 'order.details', 'order.details.product'])
                ->orderBy('updated_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => DriverDeliveryResource::collection($deliveries->items()),
                'pagination' => [
                    'current_page' => $deliveries->currentPage(),
                    'total_pages' => $deliveries->lastPage(),
                    'total_items' => $deliveries->total(),
                    'per_page' => $deliveries->perPage(),
                ],
                'message' => 'Entregas completadas',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener entregas',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Obtener detalle de una entrega
     * 
     * @route GET /api/v1/driver/deliveries/{id}
     */
    public function show(Request $request, int $id)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $delivery = Delivery::where('id', $id)
                ->where('user_id', $driver->id)
                ->with(['order', 'order.customer', 'order.details', 'order.details.product'])
                ->first();

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Entrega no encontrada o no tienes acceso',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new DeliveryDetailResource($delivery),
                'message' => 'Detalle de entrega',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener detalle',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Registrar ubicación del repartidor en tiempo real
     *
     * @route PUT /api/v1/driver/deliveries/{id}/location
     * @body { driver_lat: number, driver_lng: number, formatted_address?: string }
     */
    public function updateLocation(UpdateDeliveryLocationRequest $request, int $id)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $delivery = Delivery::where('id', $id)
                ->where('user_id', $driver->id)
                ->with(['order', 'order.customer', 'order.details', 'order.details.product'])
                ->first();

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Entrega no encontrada',
                ], 404);
            }

            $validated = $request->validated();
            $delivery->driver_lat = $validated['driver_lat'];
            $delivery->driver_lng = $validated['driver_lng'];
            $delivery->driver_location_updated_at = now();

            if (!empty($validated['formatted_address'])) {
                $delivery->notes = $validated['formatted_address'];
            }

            $delivery->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'delivery_id' => $delivery->id,
                    'driver_location' => [
                        'lat' => $delivery->driver_lat,
                        'lng' => $delivery->driver_lng,
                        'updated_at' => $delivery->driver_location_updated_at?->toIso8601String(),
                    ],
                ],
                'message' => 'Ubicación del repartidor actualizada',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al actualizar ubicación',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Actualizar estado de entrega
     * 
     * @route PUT /api/v1/driver/deliveries/{id}/status
     * @body { status: "ready|in_transit|completed|canceled", notes?: string }
     */
    public function updateStatus(UpdateDeliveryStatusRequest $request, int $id)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $delivery = Delivery::where('id', $id)
                ->where('user_id', $driver->id)
                ->with(['order', 'order.customer', 'order.details', 'order.details.product'])
                ->first();

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Entrega no encontrada',
                ], 404);
            }

            $validated = $request->validated();

            // Actualizar estado
            $delivery->status = $validated['status'];
            if (isset($validated['notes'])) {
                $delivery->notes = $validated['notes'];
            }

            if ($request->filled('destination_lat') && $request->filled('destination_lng')) {
                $delivery->destination_lat = $request->input('destination_lat');
                $delivery->destination_lng = $request->input('destination_lng');
            }

            $delivery->save();

            // Opcional: actualizar estado de la orden si la entrega se completó
            if ($validated['status'] === 'completed') {
                $delivery->order->update(['status' => 'Completed']);
            } elseif ($validated['status'] === 'canceled') {
                $delivery->order->update(['status' => 'Canceled']);
            }

            return response()->json([
                'success' => true,
                'data' => new DeliveryDetailResource($delivery),
                'message' => 'Estado de entrega actualizado',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al actualizar estado',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Obtener estadísticas del driver
     * 
     * @route GET /api/v1/driver/deliveries/stats
     */
    public function stats(Request $request)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $deliveries = Delivery::where('user_id', $driver->id);

            $stats = [
                'activas' => (clone $deliveries)->whereIn('status', ['pending', 'ready', 'in_transit'])->count(),
                'completadas' => (clone $deliveries)->where('status', 'completed')->count(),
                'canceladas' => (clone $deliveries)->where('status', 'canceled')->count(),
                'total' => (clone $deliveries)->count(),
                'ingresos_totales' => (clone $deliveries)->where('status', 'completed')->sum('total'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas del driver',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener estadísticas',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }
}
