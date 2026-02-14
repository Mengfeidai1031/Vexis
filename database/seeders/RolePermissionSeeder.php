<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para Usuarios
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // Crear permisos para Departamentos
        Permission::create(['name' => 'ver departamentos']);
        Permission::create(['name' => 'crear departamentos']);
        Permission::create(['name' => 'editar departamentos']);
        Permission::create(['name' => 'eliminar departamentos']);

        // Crear permisos para Centros
        Permission::create(['name' => 'ver centros']);
        Permission::create(['name' => 'crear centros']);
        Permission::create(['name' => 'editar centros']);
        Permission::create(['name' => 'eliminar centros']);

        // Crear permisos para Clientes
        Permission::create(['name' => 'ver clientes']);
        Permission::create(['name' => 'crear clientes']);
        Permission::create(['name' => 'editar clientes']);
        Permission::create(['name' => 'eliminar clientes']);

        // Crear permisos para Vehículos
        Permission::create(['name' => 'ver vehículos']);
        Permission::create(['name' => 'crear vehículos']);
        Permission::create(['name' => 'editar vehículos']);
        Permission::create(['name' => 'eliminar vehículos']);

        // Crear permisos para Ofertas
        Permission::create(['name' => 'ver ofertas']);
        Permission::create(['name' => 'crear ofertas']);
        Permission::create(['name' => 'editar ofertas']);
        Permission::create(['name' => 'eliminar ofertas']);

        // Crear permisos para Roles
        Permission::create(['name' => 'ver roles']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'eliminar roles']);

        // Crear rol de Super Admin (tiene todos los permisos)
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Crear rol de Administrador (gestión de usuarios y configuración)
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo([
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'ver departamentos', 'crear departamentos', 'editar departamentos', 'eliminar departamentos',
            'ver centros', 'crear centros', 'editar centros', 'eliminar centros',
            'ver roles', 'crear roles', 'editar roles',
        ]);

        // Crear rol de Gerente (puede ver y gestionar clientes, vehículos y ofertas)
        $gerenteRole = Role::create(['name' => 'Gerente']);
        $gerenteRole->givePermissionTo([
            'ver usuarios',
            'ver departamentos',
            'ver centros',
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',
            'ver vehículos', 'crear vehículos', 'editar vehículos', 'eliminar vehículos',
            'ver ofertas', 'crear ofertas', 'editar ofertas', 'eliminar ofertas',
        ]);

        // Crear rol de Vendedor (gestión de clientes y ofertas)
        $vendedorRole = Role::create(['name' => 'Vendedor']);
        $vendedorRole->givePermissionTo([
            'ver clientes', 'crear clientes', 'editar clientes',
            'ver vehículos',
            'ver ofertas', 'crear ofertas', 'editar ofertas',
        ]);

        // Crear rol de Consultor (solo lectura)
        $consultorRole = Role::create(['name' => 'Consultor']);
        $consultorRole->givePermissionTo([
            'ver usuarios',
            'ver departamentos',
            'ver centros',
            'ver clientes',
            'ver vehículos',
            'ver ofertas',
        ]);
    }
}