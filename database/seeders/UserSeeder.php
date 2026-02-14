<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Super Admin
        $superAdmin = User::create([
            'nombre' => 'Super',
            'apellidos' => 'Administrador',
            'empresa_id' => 1,
            'departamento_id' => 5, // Informática
            'centro_id' => 1,
            'email' => 'superadmin@grupoari.com',
            'telefono' => '928111111',
            'extension' => '100',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Usuario Administrador
        $admin = User::create([
            'nombre' => 'Admin',
            'apellidos' => 'Sistema',
            'empresa_id' => 1,
            'departamento_id' => 5, // Informática
            'centro_id' => 1,
            'email' => 'admin@grupoari.com',
            'telefono' => '928111112',
            'extension' => '101',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Administrador');

        // Usuario Gerente
        $gerente = User::create([
            'nombre' => 'Juan',
            'apellidos' => 'Pérez García',
            'empresa_id' => 1,
            'departamento_id' => 2, // Ventas
            'centro_id' => 1,
            'email' => 'juan@grupoari.com',
            'telefono' => '928222222',
            'extension' => '201',
            'password' => Hash::make('password'),
        ]);
        $gerente->assignRole('Gerente');

        // Usuario Vendedor
        $vendedor = User::create([
            'nombre' => 'María',
            'apellidos' => 'González López',
            'empresa_id' => 2,
            'departamento_id' => 2, // Ventas
            'centro_id' => 3,
            'email' => 'maria@grupoari.com',
            'telefono' => '922333333',
            'extension' => '301',
            'password' => Hash::make('password'),
        ]);
        $vendedor->assignRole('Vendedor');

        // Usuario Consultor
        $consultor = User::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Martínez Ruiz',
            'empresa_id' => 1,
            'departamento_id' => 1, // Administración
            'centro_id' => 1,
            'email' => 'pedro@grupoari.com',
            'telefono' => '928444444',
            'extension' => '401',
            'password' => Hash::make('password'),
        ]);
        $consultor->assignRole('Consultor');
    }
}