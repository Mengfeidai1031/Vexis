<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class TestUsersModalTest extends TestCase
{
    public function test_login_page_includes_test_users_modal(): void
    {
        $html = $this->get('/login')->getContent();
        $this->assertStringContainsString('vxTestFab', $html);
        $this->assertStringContainsString('vxTestModal', $html);
        $this->assertStringContainsString('Usuarios prueba', $html);
    }

    public function test_register_page_includes_test_users_modal(): void
    {
        $html = $this->get('/register')->getContent();
        $this->assertStringContainsString('vxTestFab', $html);
    }

    public function test_modal_contains_seeded_users(): void
    {
        $html = $this->get('/login')->getContent();
        $this->assertStringContainsString('mengfei.dai@grupo-dai.com', $html);
        $this->assertStringContainsString('carmen.santana@grupo-dai.com', $html);
        $this->assertStringContainsString('francisco.hernandez@grupo-dai.com', $html);
    }
}
