<?php

namespace Tests\Feature\Modules;

use App\Models\Reparto;
use Tests\TestCase;

class RecambiosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_index_routes(): void
    {
        foreach (['/recambios', '/almacenes', '/stocks', '/repartos'] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_create_show_edit(): void
    {
        foreach ([
            '/almacenes/create', '/stocks/create', '/repartos/create',
            '/almacenes/1', '/stocks/1', '/repartos/1',
            '/almacenes/1/edit', '/stocks/1/edit', '/repartos/1/edit',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_stock_exports(): void
    {
        $this->get('/stocks/export/excel')->assertOk();
        $this->get('/stocks/export/pdf')->assertOk();
    }

    public function test_reparto_state_transitions(): void
    {
        $r = Reparto::where('estado', '!=', 'entregado')->first();
        $this->assertNotNull($r);
        $oldState = $r->estado;
        $r->update(['estado' => 'entregado']);
        $this->assertEquals('entregado', $r->fresh()->estado);
        $r->update(['estado' => $oldState]);
    }
}
