<?php

namespace App\Http\Resources\Api\V1\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverProfileResource extends JsonResource
{
    /**
     * Datos del perfil del driver
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'email' => $this->email,
            'vehiculo' => $this->vehicle ?? null,
            'placa' => $this->license_plate ?? null,
            'entregas_totales' => $this->deliveries_count ?? 0,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
