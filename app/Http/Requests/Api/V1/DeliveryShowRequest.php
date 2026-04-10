<?php

namespace App\Http\Requests\Api\V1;

class DeliveryShowRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'order_id' => $this->route('orderId'),
        ]);
    }
}
