<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente');

        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'dni' => [
                'required',
                'string',
                'max:10',
                Rule::unique('clientes')->ignore($clienteId),
            ],
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
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe un cliente con este DNI.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener exactamente 5 dígitos.',
        ];
    }
}