<?php

namespace App\Http\Requests\Api\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'vehicle' => 'nullable|string|max:255',
            'license_plate' => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'license_plate.max' => 'La placa no puede exceder 10 caracteres',
        ];
    }
}
