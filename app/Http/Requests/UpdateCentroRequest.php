<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCentroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'direccion' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'empresa_id.exists' => 'La empresa seleccionada no existe.',
            'direccion.required' => 'La dirección es obligatoria.',
            'provincia.required' => 'La provincia es obligatoria.',
            'municipio.required' => 'El municipio es obligatorio.',
        ];
    }
}