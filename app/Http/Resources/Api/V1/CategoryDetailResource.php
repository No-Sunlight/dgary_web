<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class CategoryDetailResource extends CategoryResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['products'] = ProductResource::collection($this->whenLoaded('Products'));

        return $data;
    }
}
