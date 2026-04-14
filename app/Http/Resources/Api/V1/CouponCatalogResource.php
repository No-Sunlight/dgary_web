<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponCatalogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => (string) ($this->code ?? ('COUPON-' . $this->id)),
            'description' => $this->description,
            'discount' => (int) $this->discount,
            'points_price' => (int) $this->points_price,
            'status' => (bool) $this->status,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
