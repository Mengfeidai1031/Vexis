<?php

namespace Tests\Feature\Regression;

use App\Models\Vacacion;
use Tests\TestCase;

/**
 * REGRESSION #001 — Cálculo de días de vacaciones incluía sábados/domingos.
 *
 * Bug histórico (Fase 1.1):
 *   VacacionController::store hacía `$inicio->diffInWeekdays($fin) + 1` que daba 7 días
 *   para el rango 22/05/2026 (vie) → 31/05/2026 (dom). El usuario reportó que
 *   debían ser 6 días laborables. Causa: el +1 siempre sumaba aunque el día final
 *   fuera fin de semana.
 *
 * Fix: iteración explícita con `Carbon::isWeekend()`.
 *
 * @see App\Http\Controllers\VacacionController::store
 */
class Issue001VacacionWeekendBugTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_rango_friday_to_sunday_counts_only_weekdays(): void
    {
        // Vie 24/05/2030 → Dom 02/06/2030: 10 días cal, 6 laborables (no 7, no 10)
        $this->post('/vacaciones', [
            'fecha_inicio' => '2030-05-24',
            'fecha_fin' => '2030-06-02',
            'motivo' => 'REGRESSION-001',
        ])->assertRedirect();

        $v = Vacacion::where('motivo', 'REGRESSION-001')->latest()->first();
        $this->assertNotNull($v);
        $this->assertEquals(6, $v->dias_solicitados);
    }

    public function test_pure_weekend_range_returns_error(): void
    {
        // Sáb-Dom: 0 laborables → no debe permitir crear
        $this->post('/vacaciones', [
            'fecha_inicio' => '2030-05-25', // Sáb
            'fecha_fin' => '2030-05-26',    // Dom
            'motivo' => 'REGRESSION-001-WEEKEND-ONLY',
        ])->assertSessionHas('error');
        $this->assertNull(Vacacion::where('motivo', 'REGRESSION-001-WEEKEND-ONLY')->first());
    }
}
