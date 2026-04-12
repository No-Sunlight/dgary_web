<?php

namespace App\Http\Requests\Api\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'driver_lat' => 'required|numeric|between:-90,90',
            'driver_lng' => 'required|numeric|between:-180,180',
            'formatted_address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'driver_lat.required' => 'La latitud del repartidor es requerida',
            'driver_lng.required' => 'La longitud del repartidor es requerida',
            'driver_lat.between' => 'La latitud debe estar entre -90 y 90',
            'driver_lng.between' => 'La longitud debe estar entre -180 y 180',
        ];
    }
}
