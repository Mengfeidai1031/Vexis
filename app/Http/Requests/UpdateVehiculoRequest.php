<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Helpers\UserRestrictionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        return [
            'chasis' => [
                'required',
                'string',
                'size:17',
                Rule::unique('vehiculos')->ignore($vehiculoId),
            ],
            'matricula' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('vehiculos')->ignore($vehiculoId),
            ],
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'color_externo' => 'required|string|max:255',
            'color_interno' => 'required|string|max:255',
            'estado' => 'nullable|in:disponible,reservado,vendido,taller,baja',
            'responsable_id' => 'nullable|exists:users,id',
            'empresa_id' => [
                'required',
                'exists:empresas,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user && UserRestrictionHelper::hasRestrictionsOfType($user, UserRestrictionHelper::TYPE_EMPRESA)) {
                        if (! UserRestrictionHelper::canAccess($user, UserRestrictionHelper::TYPE_EMPRESA, $value)) {
                            $fail('No tienes permiso para asignar vehículos a esta empresa.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'chasis.required' => 'El número de chasis es obligatorio.',
            'chasis.size' => 'El número de chasis debe tener exactamente 17 caracteres.',
            'chasis.unique' => 'Ya existe un vehículo con este número de chasis.',
            'marca_id.required' => 'Debe seleccionar una marca.',
            'marca_id.exists' => 'La marca seleccionada no existe.',
            'modelo.required' => 'El modelo es obligatorio.',
            'version.required' => 'La versión es obligatoria.',
            'color_externo.required' => 'El color externo es obligatorio.',
            'color_interno.required' => 'El color interno es obligatorio.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
        ];
    }
}
