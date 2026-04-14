<?php

namespace App\Http\Requests\Api\V1;

class CouponValidateRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_id' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ];
    }
}
