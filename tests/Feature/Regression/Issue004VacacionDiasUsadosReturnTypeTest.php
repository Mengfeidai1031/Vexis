<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use App\Models\Vacacion;
use Tests\TestCase;

/**
 * REGRESSION #004 — Vacacion::diasUsados() devolvía string, función declaraba int.
 *
 * Bug histórico (Fase 8.1):
 *   MySQL sum() devuelve string en lugar de int. La firma de la función era
 *   `: int` → al renderizar /vacaciones tiraba 500 con
 *   "Return value must be of type int, string returned".
 *
 * Fix: cast `(int)` antes del return.
 *
 * @see App\Models\Vacacion::diasUsados
 */
class Issue004VacacionDiasUsadosReturnTypeTest extends TestCase
{
    public function test_vacaciones_index_renders_without_500(): void
    {
        $this->actingAsSuperAdmin();
        $this->get('/vacaciones')->assertOk();
    }

    public function test_dias_usados_returns_int(): void
    {
        $user = User::first();
        $this->assertIsInt(Vacacion::diasUsados($user->id, 2024));
        $this->assertIsInt(Vacacion::diasUsados($user->id, 2099));
    }
}
