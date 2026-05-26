<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use App\Models\User;
use App\Models\Vacacion;
use Tests\TestCase;

class VacacionTest extends TestCase
{
    public function test_dias_asignados_reads_from_setting(): void
    {
        Setting::set('dias_vacaciones_anuales', 25);
        $this->assertEquals(25, Vacacion::diasAsignados());

        Setting::set('dias_vacaciones_anuales', 22);
        $this->assertEquals(22, Vacacion::diasAsignados());
    }

    public function test_dias_usados_returns_int_not_string(): void
    {
        $user = User::first();
        $result = Vacacion::diasUsados($user->id, now()->year);
        $this->assertIsInt($result, 'diasUsados debe devolver int (regresión bug)');
    }

    public function test_dias_usados_only_counts_aprobadas(): void
    {
        $user = User::first();
        $year = 2099; // año futuro vacío

        Vacacion::create([
            'user_id' => $user->id,
            'fecha_inicio' => "$year-06-01", 'fecha_fin' => "$year-06-05",
            'dias_solicitados' => 5, 'estado' => 'aprobada',
        ]);
        Vacacion::create([
            'user_id' => $user->id,
            'fecha_inicio' => "$year-07-01", 'fecha_fin' => "$year-07-05",
            'dias_solicitados' => 5, 'estado' => 'pendiente',
        ]);
        Vacacion::create([
            'user_id' => $user->id,
            'fecha_inicio' => "$year-08-01", 'fecha_fin' => "$year-08-05",
            'dias_solicitados' => 5, 'estado' => 'rechazada',
        ]);

        $this->assertEquals(5, Vacacion::diasUsados($user->id, $year));
    }
}
