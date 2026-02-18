<?php

namespace App\Http\Requests;

use App\Helpers\UserRestrictionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente');
        $user = Auth::user();

        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => [
                'required',
                'exists:empresas,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                        if (!UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $value)) {
                            $fail('No tienes permiso para asignar clientes a esta empresa.');
                        }
                    }
                },
            ],
            'dni' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('clientes')->ignore($clienteId),
            ],
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
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
            'dni.unique' => 'Ya existe un cliente con este DNI.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede tener más de 255 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener exactamente 5 dígitos.',
        ];
    }
}