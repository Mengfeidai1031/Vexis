<?php

namespace Tests\Feature\Modules;

use Tests\TestCase;

class ClientePortalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsCliente();
    }

    public function test_portal_routes_accessible(): void
    {
        foreach ([
            '/cliente', '/cliente/chatbot', '/cliente/pretasacion',
            '/cliente/tasacion', '/cliente/configurador',
            '/cliente/noticias', '/cliente/talleres',
            '/cliente/concesionarios', '/cliente/campanias',
        ] as $r) {
            $this->get($r)->assertOk();
        }
    }

    public function test_cliente_cannot_access_admin(): void
    {
        $this->get('/users')->assertForbidden();
        $this->get('/vehiculos')->assertForbidden();
        $this->get('/facturas')->assertForbidden();
        $this->get('/settings')->assertForbidden();
    }
}
