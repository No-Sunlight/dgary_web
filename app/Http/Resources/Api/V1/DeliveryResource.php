<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'address' => $this->address,
            'formatted_address' => $this->notes,
            'status' => $this->status,
            'destination' => [
                'lat' => $this->destination_lat,
                'lng' => $this->destination_lng,
            ],
            'driver_location' => [
                'lat' => $this->driver_lat,
                'lng' => $this->driver_lng,
                'updated_at' => $this->driver_location_updated_at,
            ],
            'notes' => $this->notes,
            'total' => (float) $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
