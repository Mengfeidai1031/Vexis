<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'dni' => 'required|string|max:10|unique:clientes,dni',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'required|string|size:5',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'empresa_id.exists' => 'La empresa seleccionada no existe.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe un cliente con este DNI.',
            'dni.max' => 'El DNI no puede tener más de 10 caracteres.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener exactamente 5 dígitos.',
        ];
    }
}