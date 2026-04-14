<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'coupon_id' => 'nullable',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Carrito vacío',
            'items.min' => 'Mínimo 1 producto',
            'items.*.product_id.required' => 'Product ID requerido',
            'items.*.product_id.exists' => 'Producto inválido o no existe',
            'items.*.quantity.required' => 'Cantidad requerida',
            'items.*.quantity.min' => 'Cantidad mínima 1',
            'items.*.quantity.max' => 'Cantidad máxima 99',
            'coupon_id.exists' => 'Cupón inválido o no existe',
            'notes.max' => 'Máximo 500 caracteres en notas',
        ];
    }
}
