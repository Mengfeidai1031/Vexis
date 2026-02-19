<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\UserRestrictionHelper;
use App\Models\Centro;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

final class PrepararUsuariosPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:preparar-usuarios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepara usuarios de prueba para verificar las políticas de autorización';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Preparando usuarios de prueba ===');
        $this->newLine();

        // Obtener empresas existentes
        $empresa1 = Empresa::first();
        $empresa2 = Empresa::skip(1)->first();

        if (!$empresa1 || !$empresa2) {
            $this->error('❌ ERROR: Necesitas al menos 2 empresas en la base de datos');
            return Command::FAILURE;
        }

        // Obtener departamento y centro para los usuarios
        $departamento = Departamento::first();
        $centro = Centro::where('empresa_id', $empresa1->id)->first();

        if (!$departamento || !$centro) {
            $this->error('❌ ERROR: Necesitas al menos 1 departamento y 1 centro en la base de datos');
            return Command::FAILURE;
        }

        $this->info("Empresas encontradas:");
        $this->line("  - Empresa 1: {$empresa1->nombre} (ID: {$empresa1->id})");
        $this->line("  - Empresa 2: {$empresa2->nombre} (ID: {$empresa2->id})");
        $this->newLine();

        // Obtener roles
        $adminRole = Role::where('name', 'Administrador')->first();
        $userRole = Role::where('name', 'Usuario')->first();

        if (!$adminRole) {
            $this->error('❌ ERROR: No se encontró el rol "Administrador"');
            return Command::FAILURE;
        }

        // ============================================
        // 1. USUARIO ADMINISTRADOR (Sin restricciones)
        // ============================================
        $this->info('1. Creando usuario ADMINISTRADOR...');

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nombre' => 'Admin',
                'apellidos' => 'Sistema',
                'password' => bcrypt('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );

        $admin->syncRoles([$adminRole]);

        $this->line('   ✅ Usuario creado: admin@test.com / password');
        $this->line('   ✅ Rol: Administrador');
        $this->line('   ✅ Restricciones: Ninguna (puede ver todo)');
        $this->newLine();

        // ============================================
        // 2. USUARIO CON RESTRICCIONES (Solo empresa 1)
        // ============================================
        $this->info('2. Creando usuario CON RESTRICCIONES...');

        $userRestricted = User::updateOrCreate(
            ['email' => 'restringido@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Restringido',
                'password' => bcrypt('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );

        if ($userRole) {
            $userRestricted->syncRoles([$userRole]);
        }

        // Asignar permisos específicos
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

        // Eliminar restricciones anteriores
        UserRestrictionHelper::removeAllRestrictions($userRestricted);

        // Añadir restricción: solo puede ver empresa 1
        // Esto permitirá ver TODOS los clientes, vehículos, etc. de esa empresa
        UserRestrictionHelper::addRestriction($userRestricted, 'empresa', $empresa1->id);
        
        // NOTA: No añadimos restricciones de cliente/vehículo específicos porque
        // queremos que vea TODOS los recursos de la empresa, no solo uno específico

        $this->line('   ✅ Usuario creado: restringido@test.com / password');
        $this->line('   ✅ Rol: Usuario');
        $this->line("   ✅ Restricciones: Empresa {$empresa1->nombre}");
        $this->newLine();

        // ============================================
        // 3. USUARIO SIN RESTRICCIONES (Puede ver todo)
        // ============================================
        $this->info('3. Creando usuario SIN RESTRICCIONES...');

        $userSinRestricciones = User::updateOrCreate(
            ['email' => 'sinrestricciones@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Sin Restricciones',
                'password' => bcrypt('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
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

        // Asegurar que no tiene restricciones
        UserRestrictionHelper::removeAllRestrictions($userSinRestricciones);

        $this->line('   ✅ Usuario creado: sinrestricciones@test.com / password');
        $this->line('   ✅ Rol: Usuario');
        $this->line('   ✅ Restricciones: Ninguna (puede ver todo)');
        $this->newLine();

        // ============================================
        // 4. USUARIO SIN PERMISOS (Para probar denegación)
        // ============================================
        $this->info('4. Creando usuario SIN PERMISOS...');

        $userSinPermisos = User::updateOrCreate(
            ['email' => 'sinpermisos@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Sin Permisos',
                'password' => bcrypt('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );

        if ($userRole) {
            $userSinPermisos->syncRoles([$userRole]);
        }

        // No asignar ningún permiso
        $userSinPermisos->syncPermissions([]);

        $this->line('   ✅ Usuario creado: sinpermisos@test.com / password');
        $this->line('   ✅ Rol: Usuario');
        $this->line('   ✅ Permisos: Ninguno');
        $this->newLine();

        // ============================================
        // RESUMEN
        // ============================================
        $this->info('=== RESUMEN DE USUARIOS DE PRUEBA ===');
        $this->newLine();

        $this->line('1. ADMINISTRADOR');
        $this->line('   Email: admin@test.com');
        $this->line('   Password: password');
        $this->line('   Permisos: Todos (rol Administrador)');
        $this->line('   Restricciones: Ninguna');
        $this->line('   Puede: Ver y gestionar TODO');
        $this->newLine();

        $this->line('2. USUARIO RESTRINGIDO');
        $this->line('   Email: restringido@test.com');
        $this->line('   Password: password');
        $this->line('   Permisos: Ver/editar clientes, vehículos, ofertas, etc.');
        $this->line("   Restricciones: Solo empresa {$empresa1->nombre}");
        $this->line('   Puede: Ver solo recursos de su empresa');
        $this->newLine();

        $this->line('3. USUARIO SIN RESTRICCIONES');
        $this->line('   Email: sinrestricciones@test.com');
        $this->line('   Password: password');
        $this->line('   Permisos: Ver/editar varios recursos');
        $this->line('   Restricciones: Ninguna');
        $this->line('   Puede: Ver todos los recursos (si tiene permiso)');
        $this->newLine();

        $this->line('4. USUARIO SIN PERMISOS');
        $this->line('   Email: sinpermisos@test.com');
        $this->line('   Password: password');
        $this->line('   Permisos: Ninguno');
        $this->line('   Restricciones: Ninguna');
        $this->line('   Puede: Nada (debe ser denegado)');
        $this->newLine();

        // ============================================
        // DATOS DE PRUEBA
        // ============================================
        $this->info('=== DATOS DE PRUEBA ===');
        $this->newLine();

        $this->line("Empresa 1: {$empresa1->nombre} (ID: {$empresa1->id})");
        $this->line('  - Clientes: ' . Cliente::where('empresa_id', $empresa1->id)->count());
        $this->line('  - Vehículos: ' . Vehiculo::where('empresa_id', $empresa1->id)->count());
        $this->line('  - Centros: ' . Centro::where('empresa_id', $empresa1->id)->count());
        $this->newLine();

        $this->line("Empresa 2: {$empresa2->nombre} (ID: {$empresa2->id})");
        $this->line('  - Clientes: ' . Cliente::where('empresa_id', $empresa2->id)->count());
        $this->line('  - Vehículos: ' . Vehiculo::where('empresa_id', $empresa2->id)->count());
        $this->line('  - Centros: ' . Centro::where('empresa_id', $empresa2->id)->count());
        $this->newLine();

        $this->info('✅ Usuarios de prueba preparados correctamente!');
        $this->newLine();
        $this->line('Siguiente paso: Seguir la guía GUIA_PRUEBAS_POLITICAS.md');

        return Command::SUCCESS;
    }
}
