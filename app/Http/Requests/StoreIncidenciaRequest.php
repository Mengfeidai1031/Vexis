<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'estado' => 'required|in:abierta,en_progreso,resuelta,cerrada',
            'tecnico_id' => 'nullable|exists:users,id',
            'archivos_usuario.*' => 'nullable|file|max:10240',
            'archivos_tecnico.*' => 'nullable|file|max:10240',
        ];
    }
}
