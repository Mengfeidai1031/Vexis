<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehiculoId = $this->route('vehiculo');

        return [
            'chasis' => [
                'required',
                'string',
                'size:17',
                Rule::unique('vehiculos')->ignore($vehiculoId),
            ],
            'modelo' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'color_externo' => 'required|string|max:255',
            'color_interno' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'chasis.required' => 'El número de chasis es obligatorio.',
            'chasis.size' => 'El número de chasis debe tener exactamente 17 caracteres.',
            'chasis.unique' => 'Ya existe un vehículo con este número de chasis.',
            'modelo.required' => 'El modelo es obligatorio.',
            'version.required' => 'La versión es obligatoria.',
            'color_externo.required' => 'El color externo es obligatorio.',
            'color_interno.required' => 'El color interno es obligatorio.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
        ];
    }
}