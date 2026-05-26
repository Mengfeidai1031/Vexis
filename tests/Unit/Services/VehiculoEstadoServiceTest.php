<?php

namespace Tests\Unit\Services;

use App\Models\Vehiculo;
use App\Models\VehiculoHistorial;
use App\Services\VehiculoEstadoService;
use Tests\TestCase;

class VehiculoEstadoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_cambiar_estado_updates_and_logs_history(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $historialBefore = VehiculoHistorial::where('vehiculo_id', $vehiculo->id)->count();

        app(VehiculoEstadoService::class)->cambiarEstado($vehiculo, 'reservado', 'test unit');

        $this->assertEquals('reservado', $vehiculo->fresh()->estado);
        $this->assertEquals($historialBefore + 1, VehiculoHistorial::where('vehiculo_id', $vehiculo->id)->count());
    }

    public function test_cambiar_estado_invalid_does_nothing(): void
    {
        $vehiculo = Vehiculo::first();
        $original = $vehiculo->estado;
        app(VehiculoEstadoService::class)->cambiarEstado($vehiculo, 'estado_inexistente');
        $this->assertEquals($original, $vehiculo->fresh()->estado);
    }

    public function test_cambiar_estado_same_state_does_nothing(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $count = VehiculoHistorial::count();
        app(VehiculoEstadoService::class)->cambiarEstado($vehiculo, 'disponible');
        $this->assertEquals($count, VehiculoHistorial::count(), 'No debe crear historial si el estado no cambia');
    }

    public function test_sincronizar_con_venta_maps_states(): void
    {
        $svc = app(VehiculoEstadoService::class);
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();

        $svc->sincronizarConVenta($vehiculo, 'reservada');
        $this->assertEquals('reservado', $vehiculo->fresh()->estado);

        $svc->sincronizarConVenta($vehiculo, 'entregada');
        $this->assertEquals('vendido', $vehiculo->fresh()->estado);

        $svc->sincronizarConVenta($vehiculo, 'cancelada');
        $this->assertEquals('disponible', $vehiculo->fresh()->estado);
    }

    public function test_sincronizar_con_cita_only_moves_to_taller_on_confirmada_or_en_curso(): void
    {
        $svc = app(VehiculoEstadoService::class);
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();

        $svc->sincronizarConCita($vehiculo, 'pendiente');
        $this->assertEquals('disponible', $vehiculo->fresh()->estado, 'pendiente no debe mover');

        $svc->sincronizarConCita($vehiculo, 'confirmada');
        $this->assertEquals('taller', $vehiculo->fresh()->estado);

        $vehiculo->update(['estado' => 'disponible']);
        $svc->sincronizarConCita($vehiculo, 'en_curso');
        $this->assertEquals('taller', $vehiculo->fresh()->estado);
    }
}
