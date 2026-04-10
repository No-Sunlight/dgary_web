<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'coupon_id' => $this->coupon_id,
            'status' => (bool) $this->status,
            'discount' => (int) $this->discount,
            'coupon' => [
                'id' => $this->coupons?->id,
                'name' => $this->coupons?->name,
                'is_active' => (bool) ($this->coupons?->is_active ?? false),
                'expires_at' => $this->coupons?->expires_at,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
