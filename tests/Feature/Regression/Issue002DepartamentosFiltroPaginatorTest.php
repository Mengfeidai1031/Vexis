<?php

namespace Tests\Feature\Regression;

use Tests\TestCase;

/**
 * REGRESSION #002 — Filtro en /departamentos rompía con "Method Collection::links does not exist".
 *
 * Bug histórico (Fase 1.2):
 *   DepartamentoController::index hacía `$this->repo->all()` (Paginator) y luego
 *   `->filter(...)->values()` que devuelve Collection sin metadata de paginación.
 *   Al hacer `$departamentos->links()` en la vista petaba.
 *
 * Fix: refactor a query Eloquent con `whereBy` + `paginate->withQueryString()`.
 *
 * @see App\Http\Controllers\DepartamentoController::index
 */
class Issue002DepartamentosFiltroPaginatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_filter_by_nombre_renders_with_pagination(): void
    {
        $this->get('/departamentos?nombre=Comercial')
            ->assertOk()
            ->assertDontSee('Method.*links does not exist');
    }

    public function test_filter_by_abreviatura_works(): void
    {
        $this->get('/departamentos?abreviatura=COM')->assertOk();
    }

    public function test_sort_by_nombre_desc(): void
    {
        $this->get('/departamentos?sort_by=nombre&sort_dir=desc')->assertOk();
    }

    public function test_creado_desde_filter(): void
    {
        $this->get('/departamentos?creado_desde=2024-01-01')->assertOk();
    }
}
