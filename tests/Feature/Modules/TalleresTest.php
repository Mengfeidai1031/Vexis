<?php

namespace Tests\Feature\Modules;

use App\Models\CitaTaller;
use App\Models\CocheSustitucion;
use App\Models\ReservaSustitucion;
use App\Models\Vehiculo;
use App\Services\VehiculoEstadoService;
use Tests\TestCase;

class TalleresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_index_routes(): void
    {
        foreach (['/talleres-modulo', '/talleres', '/mecanicos', '/citas', '/coches-sustitucion'] as $r) {
            $resp = $this->get($r);
            $this->assertContains($resp->status(), [200, 302], "$r → ".$resp->status());
        }
    }

    public function test_cita_create_with_fk_cliente_and_vehiculo(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();

        $cita = CitaTaller::create([
            'mecanico_id' => 1, 'taller_id' => 1, 'empresa_id' => 1,
            'vehiculo_id' => $vehiculo->id,
            'fecha' => now()->addDay(), 'hora_inicio' => '10:00',
            'estado' => 'confirmada', 'cliente_nombre' => 'TEST',
        ]);
        $this->assertNotNull($cita->id);
    }

    public function test_vehiculo_sync_to_taller_when_cita_confirmed(): void
    {
        $vehiculo = Vehiculo::where('estado', 'disponible')->first();
        $originalState = $vehiculo->estado;

        $cita = CitaTaller::create([
            'mecanico_id' => 1, 'taller_id' => 1, 'empresa_id' => 1,
            'vehiculo_id' => $vehiculo->id,
            'fecha' => now()->addDay(), 'hora_inicio' => '10:00',
            'estado' => 'confirmada', 'cliente_nombre' => 'TEST-SYNC',
        ]);

        app(VehiculoEstadoService::class)->sincronizarConCita(
            $vehiculo->fresh(), 'confirmada', (string) $cita->id
        );
        $this->assertEquals('taller', $vehiculo->fresh()->estado);
    }

    public function test_coche_sustitucion_create_with_optional_reservation(): void
    {
        $this->post('/coches-sustitucion', [
            'matricula' => 'TST-CS-1', 'modelo' => 'TestModel',
            'marca_id' => 1, 'taller_id' => 1, 'empresa_id' => 1,
            'reservar' => '1',
            'cliente_nombre' => 'Cliente Test',
            'fecha_inicio' => now()->toDateString(),
            'fecha_fin' => now()->addDays(3)->toDateString(),
            'estado_reserva' => 'reservado',
        ])->assertRedirect();

        $coche = CocheSustitucion::where('matricula', 'TST-CS-1')->first();
        $this->assertNotNull($coche);
        $this->assertFalse($coche->disponible);
        $this->assertGreaterThan(0, ReservaSustitucion::where('coche_id', $coche->id)->count());
    }
}
