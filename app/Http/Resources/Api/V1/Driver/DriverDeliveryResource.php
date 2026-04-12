<?php

namespace App\Http\Resources\Api\V1\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverDeliveryResource extends JsonResource
{
    /**
     * Transformar delivery para listar (pedidos activos/realizados)
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_pedido' => 'Pedido #' . str_pad($this->order->id, 3, '0', STR_PAD_LEFT),
            'cliente' => [
                'nombre' => $this->order->customer->name ?? 'Sin cliente',
                'telefono' => $this->order->customer->phone ?? 'Sin teléfono',
            ],
            'ubicacion' => [
                'direccion' => $this->address,
                'lat' => $this->destination_lat,
                'lng' => $this->destination_lng,
            ],
            'driver_location' => [
                'lat' => $this->driver_lat,
                'lng' => $this->driver_lng,
                'updated_at' => optional($this->driver_location_updated_at)?->toIso8601String(),
            ],
            'total' => floatval($this->total),
            'estado' => $this->mapStatus($this->status),
            'metodo_pago' => $this->order->payment_method ?? 'No especificado',
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    private function mapStatus(string $status): string
    {
        return match($status) {
            'pending' => 'Pendiente',
            'ready' => 'Listo',
            'in_transit' => 'En tránsito',
            'completed' => 'Entregado',
            'canceled' => 'Cancelado',
            'refund' => 'Reembolso',
            default => 'Desconocido',
        };
    }
}
