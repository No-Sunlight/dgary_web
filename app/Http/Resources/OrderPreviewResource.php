<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPreviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'subtotal' => round($this->subtotal ?? 0, 2),
            'tax' => round($this->tax ?? 0, 2),
            'discount_amount' => round($this->discount_amount ?? 0, 2),
            'discount_percent' => $this->discount_percent ?? 0,
            'discount_reason' => $this->discount_reason,
            'delivery_fee' => round($this->delivery_fee ?? 0, 2),
            'total' => round($this->total ?? 0, 2),
            'coupon_info' => $this->when($this->coupon_info, $this->coupon_info),
            'items_count' => $this->items_count ?? 0,
        ];
    }
}
