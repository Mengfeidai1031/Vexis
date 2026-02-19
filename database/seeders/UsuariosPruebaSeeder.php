<?php

namespace Database\Seeders;

use App\Helpers\UserRestrictionHelper;
use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuariosPruebaSeeder extends Seeder
{
    /**
     * Crea usuarios de prueba para verificar las políticas de autorización.
     * Equivalente al comando artisan test:preparar-usuarios.
     */
    public function run(): void
    {
        $this->command->info('=== Creando usuarios de prueba ===');

        // Obtener empresas existentes
        $empresa1 = Empresa::first();
        $empresa2 = Empresa::skip(1)->first();

        if (!$empresa1 || !$empresa2) {
            $this->command->error('ERROR: Necesitas al menos 2 empresas en la base de datos.');
            return;
        }

        // Obtener departamento y centro
        $departamento = Departamento::first();
        $centro = Centro::where('empresa_id', $empresa1->id)->first();

        if (!$departamento || !$centro) {
            $this->command->error('ERROR: Necesitas al menos 1 departamento y 1 centro en la base de datos.');
            return;
        }

        // Obtener roles
        $adminRole = Role::where('name', 'Administrador')->first();
        $userRole  = Role::where('name', 'Usuario')->first();

        // ============================================
        // 1. USUARIO ADMINISTRADOR (sin restricciones)
        // ============================================
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nombre'          => 'Admin',
                'apellidos'       => 'Sistema',
                'password'        => Hash::make('password'),
                'empresa_id'      => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id'       => $centro->id,
            ]
        );

        if ($adminRole) {
            $admin->syncRoles([$adminRole]);
        }

        $this->command->info("1. Creado: admin@test.com | Rol: Administrador | Sin restricciones");

        // ============================================
        // 2. USUARIO CON RESTRICCIONES (solo empresa 1)
        // ============================================
        $userRestricted = User::updateOrCreate(
            ['email' => 'restringido@test.com'],
            [
                'nombre'          => 'Usuario',
                'apellidos'       => 'Restringido',
                'password'        => Hash::make('password'),
                'empresa_id'      => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id'       => $centro->id,
            ]
        );

        if ($userRole) {
            $userRestricted->syncRoles([$userRole]);
        }

        $userRestricted->syncPermissions([
            'ver clientes',
            'editar clientes',
            'eliminar clientes',
            'ver vehículos',
            'editar vehículos',
            'eliminar vehículos',
            'ver ofertas',
            'eliminar ofertas',
            'ver centros',
            'editar centros',
            'eliminar centros',
            'ver departamentos',
            'editar departamentos',
            'eliminar departamentos',
            'ver usuarios',
            'editar usuarios',
        ]);

        UserRestrictionHelper::removeAllRestrictions($userRestricted);
        UserRestrictionHelper::addRestriction($userRestricted, 'empresa', $empresa1->id);

        $this->command->info("2. Creado: restringido@test.com | Restricción: solo {$empresa1->nombre}");

        // ============================================
        // 3. USUARIO SIN RESTRICCIONES (ve todo)
        // ============================================
        $userSinRestricciones = User::updateOrCreate(
            ['email' => 'sinrestricciones@test.com'],
            [
                'nombre'          => 'Usuario',
                'apellidos'       => 'Sin Restricciones',
                'password'        => Hash::make('password'),
                'empresa_id'      => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id'       => $centro->id,
            ]
        );

        if ($userRole) {
            $userSinRestricciones->syncRoles([$userRole]);
        }

        $userSinRestricciones->syncPermissions([
            'ver clientes',
            'editar clientes',
            'ver vehículos',
            'editar vehículos',
            'ver ofertas',
            'ver centros',
            'editar centros',
            'ver departamentos',
            'ver usuarios',
        ]);

        UserRestrictionHelper::removeAllRestrictions($userSinRestricciones);

        $this->command->info("3. Creado: sinrestricciones@test.com | Sin restricciones");

        // ============================================
        // 4. USUARIO SIN PERMISOS (para probar denegación)
        // ============================================
        $userSinPermisos = User::updateOrCreate(
            ['email' => 'sinpermisos@test.com'],
            [
                'nombre'          => 'Usuario',
                'apellidos'       => 'Sin Permisos',
                'password'        => Hash::make('password'),
                'empresa_id'      => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id'       => $centro->id,
            ]
        );

        if ($userRole) {
            $userSinPermisos->syncRoles([$userRole]);
        }

        $userSinPermisos->syncPermissions([]);

        $this->command->info("4. Creado: sinpermisos@test.com | Sin permisos");

        $this->command->info('');
        $this->command->info('=== Usuarios de prueba creados. Contraseña: password ===');
    }
}
