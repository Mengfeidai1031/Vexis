<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

/**
 * Base TestCase para VEXIS.
 *
 * - Usa MySQL `vexis_test_db` (configurado en phpunit.xml).
 * - Aplica DatabaseTransactions: cada test corre en transacción que se rollea al final.
 *   Los datos seedeados permanecen entre tests.
 * - Provee helpers `actingAsRole()`, `actingAsSuperAdmin()`, etc.
 */
abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * Login como usuario seedeado con el rol indicado.
     * Crea uno on-the-fly si no existe ningún usuario con ese rol.
     */
    protected function actingAsRole(string $role): User
    {
        $user = User::role($role)->first();
        if (! $user) {
            $user = User::factory()->create([
                'email' => strtolower(str_replace(' ', '', $role)).'.test@grupo-dai.com',
            ]);
            $roleModel = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
            $user->assignRole($roleModel);
        }
        $this->actingAs($user);

        return $user;
    }

    protected function actingAsSuperAdmin(): User
    {
        return $this->actingAsRole('Super Admin');
    }

    protected function actingAsAdmin(): User
    {
        return $this->actingAsRole('Administrador');
    }

    protected function actingAsGerente(): User
    {
        return $this->actingAsRole('Gerente');
    }

    protected function actingAsVendedor(): User
    {
        return $this->actingAsRole('Vendedor');
    }

    protected function actingAsConsultor(): User
    {
        return $this->actingAsRole('Consultor');
    }

    protected function actingAsMecanico(): User
    {
        return $this->actingAsRole('Mecánico');
    }

    protected function actingAsRecepcion(): User
    {
        return $this->actingAsRole('Recepción Taller');
    }

    protected function actingAsCliente(): User
    {
        $user = User::role('Cliente')->first();
        if (! $user) {
            $user = User::factory()->create([
                'email' => 'cliente.testsuite@grupo-dai.com',
            ]);
            $role = Role::firstOrCreate(['name' => 'Cliente', 'guard_name' => 'web']);
            $user->assignRole($role);
        }
        $this->actingAs($user);

        return $user;
    }
}
