<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertStatus(200)->assertSee('Iniciar Sesión');
    }

    public function test_login_with_valid_credentials_redirects(): void
    {
        $user = User::where('email', 'mengfei.dai@grupo-dai.com')->first();
        $this->assertNotNull($user, 'Super Admin seed user not found');

        $resp = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $resp->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $resp = $this->post('/login', [
            'email' => 'mengfei.dai@grupo-dai.com',
            'password' => 'wrong-password',
        ]);
        $resp->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_logout_works(): void
    {
        $this->actingAsSuperAdmin();
        $this->post('/logout')->assertRedirect();
        $this->assertGuest();
    }

    public function test_dashboard_requires_auth(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
