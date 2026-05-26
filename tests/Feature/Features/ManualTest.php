<?php

namespace Tests\Feature\Features;

use Tests\TestCase;

class ManualTest extends TestCase
{
    public function test_manual_accessible_to_authenticated_users(): void
    {
        $this->actingAsSuperAdmin();
        $resp = $this->get('/manual');
        $resp->assertOk()->assertSee('Manual de Usuario');
    }

    public function test_manual_accessible_to_cliente(): void
    {
        $this->actingAsCliente();
        $this->get('/manual')->assertOk();
    }

    public function test_manual_contains_key_sections(): void
    {
        $this->actingAsAdmin();
        $html = $this->get('/manual')->getContent();
        foreach ([
            'Bienvenida', 'Roles y permisos', 'Módulo Gestión',
            'Módulo Comercial', 'Flujo: Venta', 'Ciclo de vida',
            'Chatbot y pretasación',
        ] as $section) {
            $this->assertStringContainsString($section, $html, "Falta sección: $section");
        }
    }

    public function test_manual_unauthenticated_redirects_to_login(): void
    {
        $this->get('/manual')->assertRedirect('/login');
    }
}
