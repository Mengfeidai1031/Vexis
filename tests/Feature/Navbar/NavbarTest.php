<?php

namespace Tests\Feature\Navbar;

use Tests\TestCase;

class NavbarTest extends TestCase
{
    public function test_super_admin_sees_all_navbar_icons(): void
    {
        $this->actingAsSuperAdmin();
        $html = $this->get('/dashboard')->getContent();
        $this->assertStringContainsString('bi-book', $html, 'Manual icon missing');
        $this->assertStringContainsString('bi-exclamation-triangle', $html, 'Incidencias icon missing');
        $this->assertStringContainsString('bi-journal-text', $html, 'LogViewer icon missing');
        $this->assertStringContainsString('bi-cpu', $html, 'AI control icon missing');
    }

    public function test_admin_sees_manual_and_incidencias_only(): void
    {
        $this->actingAsAdmin();
        $html = $this->get('/dashboard')->getContent();
        $this->assertStringContainsString('bi-book', $html);
        $this->assertStringContainsString('bi-exclamation-triangle', $html);
        // No ve logs ni IA control
        $this->assertStringNotContainsString(route('logs.index'), $html);
        $this->assertStringNotContainsString(route('ai.control'), $html);
    }

    public function test_cliente_role_does_not_see_admin_icons(): void
    {
        $this->actingAsCliente();
        $html = $this->get('/cliente')->getContent();
        $this->assertStringNotContainsString(route('logs.index'), $html);
        $this->assertStringNotContainsString(route('ai.control'), $html);
        $this->assertStringNotContainsString(route('settings.index'), $html);
    }
}
