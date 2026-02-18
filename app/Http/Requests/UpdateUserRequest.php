<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user'); // Obtiene el ID de la ruta

        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'centro_id' => 'required|exists:centros,id',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'telefono' => 'nullable|string|max:12',
            'extension' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'centro_id.required' => 'Debe seleccionar un centro.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}