<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'abreviatura' => 'required|string|max:10|unique:departamentos,abreviatura',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un departamento con este nombre.',
            'abreviatura.required' => 'La abreviatura es obligatoria.',
            'abreviatura.max' => 'La abreviatura no puede tener más de 10 caracteres.',
            'abreviatura.unique' => 'Ya existe un departamento con esta abreviatura.',
        ];
    }
}