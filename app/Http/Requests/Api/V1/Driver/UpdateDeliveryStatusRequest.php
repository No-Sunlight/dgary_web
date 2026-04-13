<?php

namespace App\Http\Requests\Api\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['ready', 'in_transit', 'completed', 'canceled']),
            ],
            'notes' => 'nullable|string|max:500',
            'destination_lat' => 'nullable|numeric|between:-90,90',
            'destination_lng' => 'nullable|numeric|between:-180,180',
            'signature' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado debe ser: ready, in_transit, completed o canceled',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'La imagen debe ser JPEG o PNG',
            'photo.max' => 'La imagen no puede exceder 5MB',
        ];
    }
}
