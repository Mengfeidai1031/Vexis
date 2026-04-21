<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vehiculo;
use App\Models\VehiculoHistorial;
use Illuminate\Support\Facades\Auth;

/**
 * Gestiona transiciones de estado y auditoría del ciclo de vida del vehículo.
 */
class VehiculoEstadoService
{
    public function cambiarEstado(Vehiculo $vehiculo, string $nuevoEstado, ?string $observaciones = null): void
    {
        if (! array_key_exists($nuevoEstado, Vehiculo::$estados)) {
            return;
        }
        $anterior = $vehiculo->estado;
        if ($anterior === $nuevoEstado) {
            return;
        }
        $vehiculo->update(['estado' => $nuevoEstado]);

        VehiculoHistorial::create([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'accion' => 'cambio_estado',
            'campo' => 'estado',
            'valor_anterior' => $anterior,
            'valor_nuevo' => $nuevoEstado,
            'observaciones' => $observaciones,
        ]);
    }

    public function registrar(Vehiculo $vehiculo, string $accion, ?string $observaciones = null, array $extra = []): void
    {
        VehiculoHistorial::create(array_merge([
            'vehiculo_id' => $vehiculo->id,
            'user_id' => Auth::id(),
            'accion' => $accion,
            'observaciones' => $observaciones,
        ], $extra));
    }

    /**
     * Sincroniza el estado del vehículo según el estado de la venta asociada.
     */
    public function sincronizarConVenta(Vehiculo $vehiculo, string $estadoVenta): void
    {
        $mapa = [
            'reservada' => 'reservado',
            'pendiente_entrega' => 'reservado',
            'entregada' => 'vendido',
            'cancelada' => 'disponible',
        ];

        if (isset($mapa[$estadoVenta])) {
            $this->cambiarEstado($vehiculo, $mapa[$estadoVenta], 'Sincronización con venta (estado '.$estadoVenta.')');
        }
    }
}
