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
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);

        // Crear permisos para Departamentos
        Permission::firstOrCreate(['name' => 'ver departamentos']);
        Permission::firstOrCreate(['name' => 'crear departamentos']);
        Permission::firstOrCreate(['name' => 'editar departamentos']);
        Permission::firstOrCreate(['name' => 'eliminar departamentos']);

        // Crear permisos para Centros
        Permission::firstOrCreate(['name' => 'ver centros']);
        Permission::firstOrCreate(['name' => 'crear centros']);
        Permission::firstOrCreate(['name' => 'editar centros']);
        Permission::firstOrCreate(['name' => 'eliminar centros']);

        // Crear permisos para Clientes
        Permission::firstOrCreate(['name' => 'ver clientes']);
        Permission::firstOrCreate(['name' => 'crear clientes']);
        Permission::firstOrCreate(['name' => 'editar clientes']);
        Permission::firstOrCreate(['name' => 'eliminar clientes']);

        // Crear permisos para Vehículos
        Permission::firstOrCreate(['name' => 'ver vehículos']);
        Permission::firstOrCreate(['name' => 'crear vehículos']);
        Permission::firstOrCreate(['name' => 'editar vehículos']);
        Permission::firstOrCreate(['name' => 'eliminar vehículos']);

        // Crear permisos para Ofertas
        Permission::firstOrCreate(['name' => 'ver ofertas']);
        Permission::firstOrCreate(['name' => 'crear ofertas']);
        Permission::firstOrCreate(['name' => 'editar ofertas']);
        Permission::firstOrCreate(['name' => 'eliminar ofertas']);

        // Crear permisos para Empresas
        Permission::firstOrCreate(['name' => 'ver empresas']);
        Permission::firstOrCreate(['name' => 'crear empresas']);
        Permission::firstOrCreate(['name' => 'editar empresas']);
        Permission::firstOrCreate(['name' => 'eliminar empresas']);

        // Crear permisos para Roles
        Permission::firstOrCreate(['name' => 'ver roles']);
        Permission::firstOrCreate(['name' => 'crear roles']);
        Permission::firstOrCreate(['name' => 'editar roles']);
        Permission::firstOrCreate(['name' => 'eliminar roles']);

        // Crear permisos para Restricciones
        Permission::firstOrCreate(['name' => 'ver restricciones']);
        Permission::firstOrCreate(['name' => 'crear restricciones']);
        Permission::firstOrCreate(['name' => 'editar restricciones']);
        Permission::firstOrCreate(['name' => 'eliminar restricciones']);

        // Crear permisos para Noticias
        Permission::firstOrCreate(['name' => 'ver noticias']);
        Permission::firstOrCreate(['name' => 'crear noticias']);
        Permission::firstOrCreate(['name' => 'editar noticias']);
        Permission::firstOrCreate(['name' => 'eliminar noticias']);

        // Crear permisos para Campañas
        Permission::firstOrCreate(['name' => 'ver campanias']);
        Permission::firstOrCreate(['name' => 'crear campanias']);
        Permission::firstOrCreate(['name' => 'editar campanias']);
        Permission::firstOrCreate(['name' => 'eliminar campanias']);

        // Crear permisos para Naming PCs
        Permission::firstOrCreate(['name' => 'ver naming-pcs']);
        Permission::firstOrCreate(['name' => 'crear naming-pcs']);
        Permission::firstOrCreate(['name' => 'editar naming-pcs']);
        Permission::firstOrCreate(['name' => 'eliminar naming-pcs']);

        // Crear permisos para Festivos
        Permission::firstOrCreate(['name' => 'ver festivos']);
        Permission::firstOrCreate(['name' => 'crear festivos']);
        Permission::firstOrCreate(['name' => 'editar festivos']);
        Permission::firstOrCreate(['name' => 'eliminar festivos']);

        // Crear permisos para Almacenes
        Permission::firstOrCreate(['name' => 'ver almacenes']);
        Permission::firstOrCreate(['name' => 'crear almacenes']);
        Permission::firstOrCreate(['name' => 'editar almacenes']);
        Permission::firstOrCreate(['name' => 'eliminar almacenes']);

        // Crear permisos para Stocks
        Permission::firstOrCreate(['name' => 'ver stocks']);
        Permission::firstOrCreate(['name' => 'crear stocks']);
        Permission::firstOrCreate(['name' => 'editar stocks']);
        Permission::firstOrCreate(['name' => 'eliminar stocks']);

        // Crear permisos para Repartos
        Permission::firstOrCreate(['name' => 'ver repartos']);
        Permission::firstOrCreate(['name' => 'crear repartos']);
        Permission::firstOrCreate(['name' => 'editar repartos']);
        Permission::firstOrCreate(['name' => 'eliminar repartos']);

        // Crear permisos para Talleres
        Permission::firstOrCreate(['name' => 'ver talleres']);
        Permission::firstOrCreate(['name' => 'crear talleres']);
        Permission::firstOrCreate(['name' => 'editar talleres']);
        Permission::firstOrCreate(['name' => 'eliminar talleres']);

        // Crear permisos para Mecánicos
        Permission::firstOrCreate(['name' => 'ver mecanicos']);
        Permission::firstOrCreate(['name' => 'crear mecanicos']);
        Permission::firstOrCreate(['name' => 'editar mecanicos']);
        Permission::firstOrCreate(['name' => 'eliminar mecanicos']);

        // Crear permisos para Citas Taller
        Permission::firstOrCreate(['name' => 'ver citas']);
        Permission::firstOrCreate(['name' => 'crear citas']);
        Permission::firstOrCreate(['name' => 'editar citas']);
        Permission::firstOrCreate(['name' => 'eliminar citas']);

        // Crear permisos para Coches Sustitución
        Permission::firstOrCreate(['name' => 'ver coches-sustitucion']);
        Permission::firstOrCreate(['name' => 'crear coches-sustitucion']);
        Permission::firstOrCreate(['name' => 'editar coches-sustitucion']);
        Permission::firstOrCreate(['name' => 'eliminar coches-sustitucion']);

        // Crear permisos para Ventas
        Permission::firstOrCreate(['name' => 'ver ventas']);
        Permission::firstOrCreate(['name' => 'crear ventas']);
        Permission::firstOrCreate(['name' => 'editar ventas']);
        Permission::firstOrCreate(['name' => 'eliminar ventas']);

        // Crear permisos para Tasaciones
        Permission::firstOrCreate(['name' => 'ver tasaciones']);
        Permission::firstOrCreate(['name' => 'crear tasaciones']);
        Permission::firstOrCreate(['name' => 'editar tasaciones']);
        Permission::firstOrCreate(['name' => 'eliminar tasaciones']);

        // Crear permisos para Catálogo de Precios
        Permission::firstOrCreate(['name' => 'ver catalogo-precios']);
        Permission::firstOrCreate(['name' => 'crear catalogo-precios']);
        Permission::firstOrCreate(['name' => 'editar catalogo-precios']);
        Permission::firstOrCreate(['name' => 'eliminar catalogo-precios']);

        // Crear rol de Super Admin (tiene todos los permisos)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Crear rol de Administrador (gestión de usuarios y configuración)
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $adminRole->syncPermissions([
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'ver departamentos', 'crear departamentos', 'editar departamentos', 'eliminar departamentos',
            'ver centros', 'crear centros', 'editar centros', 'eliminar centros',
            'ver empresas', 'crear empresas', 'editar empresas', 'eliminar empresas',
            'ver roles', 'crear roles', 'editar roles',
            'ver restricciones', 'crear restricciones', 'editar restricciones', 'eliminar restricciones',
            'ver noticias', 'crear noticias', 'editar noticias', 'eliminar noticias',
            'ver campanias', 'crear campanias', 'editar campanias', 'eliminar campanias',
            'ver naming-pcs', 'crear naming-pcs', 'editar naming-pcs', 'eliminar naming-pcs',
            'ver festivos', 'crear festivos', 'editar festivos', 'eliminar festivos',
            'ver almacenes', 'crear almacenes', 'editar almacenes', 'eliminar almacenes',
            'ver stocks', 'crear stocks', 'editar stocks', 'eliminar stocks',
            'ver repartos', 'crear repartos', 'editar repartos', 'eliminar repartos',
            'ver talleres', 'crear talleres', 'editar talleres', 'eliminar talleres',
            'ver mecanicos', 'crear mecanicos', 'editar mecanicos', 'eliminar mecanicos',
            'ver citas', 'crear citas', 'editar citas', 'eliminar citas',
            'ver coches-sustitucion', 'crear coches-sustitucion', 'editar coches-sustitucion', 'eliminar coches-sustitucion',
            'ver ventas', 'crear ventas', 'editar ventas', 'eliminar ventas',
            'ver tasaciones', 'crear tasaciones', 'editar tasaciones', 'eliminar tasaciones',
            'ver catalogo-precios', 'crear catalogo-precios', 'editar catalogo-precios', 'eliminar catalogo-precios',
        ]);

        // Crear rol de Gerente (puede ver y gestionar clientes, vehículos y ofertas)
        $gerenteRole = Role::firstOrCreate(['name' => 'Gerente']);
        $gerenteRole->syncPermissions([
            'ver usuarios',
            'ver departamentos',
            'ver centros',
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',
            'ver vehículos', 'crear vehículos', 'editar vehículos', 'eliminar vehículos',
            'ver ofertas', 'crear ofertas', 'editar ofertas', 'eliminar ofertas',
        ]);

        // Crear rol de Vendedor (gestión de clientes y ofertas)
        $vendedorRole = Role::firstOrCreate(['name' => 'Vendedor']);
        $vendedorRole->syncPermissions([
            'ver clientes', 'crear clientes', 'editar clientes',
            'ver vehículos',
            'ver ofertas', 'crear ofertas', 'editar ofertas',
        ]);

        // Crear rol de Consultor (solo lectura)
        $consultorRole = Role::firstOrCreate(['name' => 'Consultor']);
        $consultorRole->syncPermissions([
            'ver usuarios',
            'ver departamentos',
            'ver centros',
            'ver clientes',
            'ver vehículos',
            'ver ofertas',
            'ver noticias',
            'ver campanias',
            'ver festivos',
        ]);
    }
}