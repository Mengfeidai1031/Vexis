<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Vehiculo;
use App\Models\VehiculoHistorial;
use Illuminate\Support\Facades\Auth;

class VehiculoObserver
{
    private const CAMPOS_AUDITADOS = [
        'matricula', 'modelo', 'version', 'color_externo', 'color_interno',
        'empresa_id', 'marca_id', 'estado', 'responsable_id',
    ];

    public function created(Vehiculo $vehiculo): void
    {
        VehiculoHistorial::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'accion' => 'creado',
            'observaciones' => 'Vehículo creado en el sistema',
        ]);
    }

    public function updated(Vehiculo $vehiculo): void
    {
        foreach ($vehiculo->getChanges() as $campo => $nuevo) {
            if (! in_array($campo, self::CAMPOS_AUDITADOS, true)) {
                continue;
            }
            $anterior = $vehiculo->getOriginal($campo);
            if ($campo === 'estado') {
                continue;
            }
            VehiculoHistorial::create([
                'vehiculo_id' => $vehiculo->id,
                'user_id' => Auth::id(),
                'accion' => 'actualizado',
                'campo' => $campo,
                'valor_anterior' => is_null($anterior) ? null : (string) $anterior,
                'valor_nuevo' => is_null($nuevo) ? null : (string) $nuevo,
            ]);
        }
    }

    public function deleted(Vehiculo $vehiculo): void
    {
        VehiculoHistorial::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'accion' => 'eliminado',
            'observaciones' => 'Vehículo dado de baja',
        ]);
    }
}
