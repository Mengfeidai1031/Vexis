<?php

namespace Tests\Feature\Modules;

use Tests\TestCase;

class DataxisTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsSuperAdmin();
    }

    public function test_dashboards_render(): void
    {
        foreach ([
            '/dataxis', '/dataxis/general', '/dataxis/ventas', '/dataxis/stock',
            '/dataxis/taller', '/dataxis/facturas', '/dataxis/incidencias',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_dashboard_includes_chartjs(): void
    {
        $html = $this->get('/dataxis/general')->getContent();
        $this->assertStringContainsString('chart.js', strtolower($html));
    }
}
