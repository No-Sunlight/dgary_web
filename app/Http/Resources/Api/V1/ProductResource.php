<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'category' => $this->category?->name,
            'price' => (float) $this->price,
            'stock' => (float) $this->stock,
            'points' => (int) $this->points,
            'estimatedTime' => '10-25 min Estimación',
            'imageUrl' => $this->image,
            'status' => (bool) $this->status,
        ];
    }
}
