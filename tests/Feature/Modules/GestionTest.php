<?php

namespace Tests\Feature\Modules;

use App\Models\Departamento;
use App\Models\Festivo;
use App\Models\Vacacion;
use Tests\TestCase;

class GestionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_gestion_index_routes(): void
    {
        foreach ([
            '/gestion', '/users', '/departamentos', '/centros', '/empresas',
            '/festivos', '/vacaciones', '/naming-pcs', '/noticias',
            '/incidencias', '/permisos', '/gestion/logs', '/settings',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_gestion_create_routes(): void
    {
        foreach ([
            '/users/create', '/departamentos/create', '/centros/create',
            '/empresas/create', '/festivos/create', '/vacaciones/create',
            '/naming-pcs/create', '/noticias/create', '/incidencias/create',
            '/permisos/create',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_gestion_show_routes(): void
    {
        foreach ([
            '/users/1', '/departamentos/1', '/centros/1', '/empresas/1',
            '/festivos/1', '/naming-pcs/1', '/noticias/1', '/incidencias/1',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_gestion_edit_routes(): void
    {
        foreach ([
            '/users/1/edit', '/departamentos/1/edit', '/centros/1/edit',
            '/empresas/1/edit', '/festivos/1/edit', '/naming-pcs/1/edit',
            '/noticias/1/edit', '/incidencias/1/edit',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_departamento_crud(): void
    {
        $resp = $this->post('/departamentos', ['nombre' => 'TEST-Dep', 'abreviatura' => 'TST']);
        $resp->assertRedirect();
        $d = Departamento::where('nombre', 'TEST-Dep')->first();
        $this->assertNotNull($d);

        $this->put("/departamentos/{$d->id}", ['nombre' => 'TEST-Dep-Up', 'abreviatura' => 'TST'])->assertRedirect();
        $this->assertEquals('TEST-Dep-Up', $d->fresh()->nombre);

        $this->delete("/departamentos/{$d->id}")->assertRedirect();
        $this->assertNull(Departamento::find($d->id));
    }

    public function test_departamento_filter(): void
    {
        $this->get('/departamentos?nombre=Comercial')->assertOk();
        $this->get('/departamentos?sort_by=nombre&sort_dir=desc')->assertOk();
    }

    public function test_festivo_crud_computes_anio_from_fecha(): void
    {
        $this->post('/festivos', [
            'nombre' => 'TEST-Festivo',
            'fecha' => '2027-08-15',
            'ambito' => 'nacional',
        ])->assertRedirect();
        $f = Festivo::where('nombre', 'TEST-Festivo')->first();
        $this->assertNotNull($f);
        $this->assertEquals(2027, $f->anio);
        $f->delete();
    }

    public function test_vacacion_excludes_weekends(): void
    {
        // Rango con fines de semana incluidos
        // Vie 22/05/2026 → Dom 31/05/2026 = 6 días laborables (no 10, no 7)
        $this->post('/vacaciones', [
            'fecha_inicio' => '2030-05-24', // Vie
            'fecha_fin' => '2030-06-02',    // Lun = 10 días cal, 6 lab
            'motivo' => 'TEST-WEEKEND',
        ])->assertRedirect();

        $v = Vacacion::where('motivo', 'TEST-WEEKEND')->latest()->first();
        $this->assertNotNull($v);
        $this->assertEquals(6, $v->dias_solicitados, 'Rango con weekend debe contar solo días laborables');
    }

    public function test_settings_helper(): void
    {
        $this->assertSame(8, (int) setting('password_min_length'));
        $this->assertSame(22, (int) setting('dias_vacaciones_anuales'));
        $this->assertSame('A', setting('factura_serie_actual'));
    }

    public function test_logs_viewer_renders(): void
    {
        $this->get('/gestion/logs')->assertOk()->assertSee('Visor');
    }
}
