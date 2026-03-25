<?php

/**
 * Script para preparar usuarios de prueba para las políticas
 * 
 * Uso: php artisan tinker < scripts/preparar_usuarios_prueba.php
 * O copiar y pegar el contenido en tinker
 */

use App\Models\User;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Centro;
use App\Models\Departamento;
use App\Helpers\UserRestrictionHelper;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== Preparando usuarios de prueba ===\n\n";

// Obtener empresas existentes
$empresa1 = Empresa::first();
$empresa2 = Empresa::skip(1)->first();

if (!$empresa1 || !$empresa2) {
    echo "ERROR: Necesitas al menos 2 empresas en la base de datos\n";
    exit;
}

echo "Empresas encontradas:\n";
echo "- Empresa 1: {$empresa1->nombre} (ID: {$empresa1->id})\n";
echo "- Empresa 2: {$empresa2->nombre} (ID: {$empresa2->id})\n\n";

// Obtener roles
$adminRole = Role::where('name', 'Administrador')->first();
$userRole = Role::where('name', 'Usuario')->first();

if (!$adminRole) {
    echo "ERROR: No se encontró el rol 'Administrador'\n";
    exit;
}

// ============================================
// 1. USUARIO ADMINISTRADOR (Sin restricciones)
// ============================================
echo "1. Creando usuario ADMINISTRADOR...\n";

$admin = User::updateOrCreate(
    ['email' => 'admin@test.com'],
    [
        'nombre' => 'Admin',
        'apellidos' => 'Sistema',
        'password' => bcrypt('password'),
        'empresa_id' => $empresa1->id,
    ]
);

$admin->syncRoles([$adminRole]);

echo "Usuario creado: admin@test.com / password\n";
echo "Rol: Administrador\n";
echo "Restricciones: Ninguna (puede ver todo)\n\n";

// ============================================
// 2. USUARIO CON RESTRICCIONES (Solo empresa 1)
// ============================================
echo "2. Creando usuario CON RESTRICCIONES...\n";

$userRestricted = User::updateOrCreate(
    ['email' => 'restringido@test.com'],
    [
        'nombre' => 'Usuario',
        'apellidos' => 'Restringido',
        'password' => bcrypt('password'),
        'empresa_id' => $empresa1->id,
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
    'ver ofertas',
    'eliminar ofertas',
    'ver centros',
    'editar centros',
    'ver departamentos',
    'editar departamentos',
    'ver usuarios',
]);

// Eliminar restricciones anteriores
UserRestrictionHelper::removeAllRestrictions($userRestricted);

// Añadir restricción: solo puede ver empresa 1
UserRestrictionHelper::addRestriction($userRestricted, 'empresa', $empresa1->id);

// Añadir restricción de un cliente específico (opcional)
$cliente1 = Cliente::where('empresa_id', $empresa1->id)->first();
if ($cliente1) {
    UserRestrictionHelper::addRestriction($userRestricted, 'cliente', $cliente1->id);
    echo "Restricción añadida: Cliente {$cliente1->nombre_completo}\n";
}

// Añadir restricción de un vehículo específico (opcional)
$vehiculo1 = Vehiculo::where('empresa_id', $empresa1->id)->first();
if ($vehiculo1) {
    UserRestrictionHelper::addRestriction($userRestricted, 'vehiculo', $vehiculo1->id);
    echo "Restricción añadida: Vehículo {$vehiculo1->modelo} {$vehiculo1->version}\n";
}

echo "Usuario creado: restringido@test.com / password\n";
echo "Rol: Usuario\n";
echo "Restricciones: Empresa {$empresa1->nombre}\n\n";

// ============================================
// 3. USUARIO SIN RESTRICCIONES (Puede ver todo)
// ============================================
echo "3. Creando usuario SIN RESTRICCIONES...\n";

$userSinRestricciones = User::updateOrCreate(
    ['email' => 'sinrestricciones@test.com'],
    [
        'nombre' => 'Usuario',
        'apellidos' => 'Sin Restricciones',
        'password' => bcrypt('password'),
        'empresa_id' => $empresa1->id,
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

echo "Usuario creado: sinrestricciones@test.com / password\n";
echo "Rol: Usuario\n";
echo "Restricciones: Ninguna (puede ver todo)\n\n";

// ============================================
// 4. USUARIO SIN PERMISOS (Para probar denegación)
// ============================================
echo "4. Creando usuario SIN PERMISOS...\n";

$userSinPermisos = User::updateOrCreate(
    ['email' => 'sinpermisos@test.com'],
    [
        'nombre' => 'Usuario',
        'apellidos' => 'Sin Permisos',
        'password' => bcrypt('password'),
        'empresa_id' => $empresa1->id,
    ]
);

if ($userRole) {
    $userSinPermisos->syncRoles([$userRole]);
}

// No asignar ningún permiso
$userSinPermisos->syncPermissions([]);

echo "Usuario creado: sinpermisos@test.com / password\n";
echo "Rol: Usuario\n";
echo "Permisos: Ninguno\n\n";

// ============================================
// RESUMEN
// ============================================
echo "=== RESUMEN DE USUARIOS DE PRUEBA ===\n\n";
echo "1. ADMINISTRADOR\n";
echo "   Email: admin@test.com\n";
echo "   Password: password\n";
echo "   Permisos: Todos (rol Administrador)\n";
echo "   Restricciones: Ninguna\n";
echo "   Puede: Ver y gestionar TODO\n\n";

echo "2. USUARIO RESTRINGIDO\n";
echo "   Email: restringido@test.com\n";
echo "   Password: password\n";
echo "   Permisos: Ver/editar clientes, vehículos, ofertas, etc.\n";
echo "   Restricciones: Solo empresa {$empresa1->nombre}\n";
echo "   Puede: Ver solo recursos de su empresa\n\n";

echo "3. USUARIO SIN RESTRICCIONES\n";
echo "   Email: sinrestricciones@test.com\n";
echo "   Password: password\n";
echo "   Permisos: Ver/editar varios recursos\n";
echo "   Restricciones: Ninguna\n";
echo "   Puede: Ver todos los recursos (si tiene permiso)\n\n";

echo "4. USUARIO SIN PERMISOS\n";
echo "   Email: sinpermisos@test.com\n";
echo "   Password: password\n";
echo "   Permisos: Ninguno\n";
echo "   Restricciones: Ninguna\n";
echo "   Puede: Nada (debe ser denegado)\n\n";

echo "=== DATOS DE PRUEBA ===\n\n";
echo "Empresa 1: {$empresa1->nombre} (ID: {$empresa1->id})\n";
echo "  - Clientes: " . Cliente::where('empresa_id', $empresa1->id)->count() . "\n";
echo "  - Vehículos: " . Vehiculo::where('empresa_id', $empresa1->id)->count() . "\n";
echo "  - Centros: " . Centro::where('empresa_id', $empresa1->id)->count() . "\n\n";

echo "Empresa 2: {$empresa2->nombre} (ID: {$empresa2->id})\n";
echo "  - Clientes: " . Cliente::where('empresa_id', $empresa2->id)->count() . "\n";
echo "  - Vehículos: " . Vehiculo::where('empresa_id', $empresa2->id)->count() . "\n";
echo "  - Centros: " . Centro::where('empresa_id', $empresa2->id)->count() . "\n\n";

echo "Usuarios de prueba preparados correctamente!\n";
echo "\nSiguiente paso: Seguir la guía GUIA_PRUEBAS_POLITICAS.md\n";
