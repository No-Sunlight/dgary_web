<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponValidationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'coupon_id' => $this['coupon_id'],
            'coupon_name' => $this['coupon_name'],
            'discount_percent' => $this['discount_percent'],
            'subtotal' => $this['subtotal'],
            'discount_amount' => $this['discount_amount'],
            'total' => $this['total'],
        ];
    }
}
