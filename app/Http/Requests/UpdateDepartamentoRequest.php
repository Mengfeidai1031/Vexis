<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departamentoId = $this->route('departamento');

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departamentos')->ignore($departamentoId),
            ],
            'abreviatura' => [
                'required',
                'string',
                'max:10',
                Rule::unique('departamentos')->ignore($departamentoId),
            ],
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