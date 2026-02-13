<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'nombre' => 'Admin',
            'apellidos' => 'Sistema',
            'empresa_id' => 1,
            'departamento_id' => 5, // Informática
            'centro_id' => 1,
            'email' => 'admin@grupoari.com',
            'telefono' => '928111111',
            'extension' => '101',
            'password' => Hash::make('password'), // Contraseña: password
        ]);

        // Usuario de prueba
        User::create([
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

        // Otro usuario de prueba
        User::create([
            'nombre' => 'María',
            'apellidos' => 'González López',
            'empresa_id' => 2,
            'departamento_id' => 1, // Administración
            'centro_id' => 3,
            'email' => 'maria@grupoari.com',
            'telefono' => '922333333',
            'extension' => '301',
            'password' => Hash::make('password'),
        ]);
    }
}