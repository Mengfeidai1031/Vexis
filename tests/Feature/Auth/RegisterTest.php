<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_page_renders(): void
    {
        $this->get('/register')->assertStatus(200)->assertSee('Crear Cuenta');
    }

    public function test_register_creates_cliente_user(): void
    {
        $resp = $this->post('/register', [
            'nombre' => 'TestReg',
            'apellidos' => 'Suite',
            'email' => 'test.register@grupo-dai.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $resp->assertRedirect();

        $user = User::where('email', 'test.register@grupo-dai.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Cliente'));
    }

    public function test_register_rejects_short_password(): void
    {
        $resp = $this->post('/register', [
            'nombre' => 'X',
            'apellidos' => 'Y',
            'email' => 'short.pwd@grupo-dai.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);
        $resp->assertSessionHasErrors('password');
    }
}
