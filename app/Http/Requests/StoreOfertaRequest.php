<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Máximo 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no existe.',
            'pdf_file.required' => 'Debe seleccionar un archivo PDF.',
            'pdf_file.mimes' => 'El archivo debe ser un PDF.',
            'pdf_file.max' => 'El archivo no puede superar los 10MB.',
        ];
    }
}