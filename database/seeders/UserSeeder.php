<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin - IT Gran Canaria
        $superAdmin = User::create([
            'nombre' => 'Meng Fei',
            'apellidos' => 'Li Zhang',
            'empresa_id' => 1,
            'departamento_id' => 7, // Informática
            'centro_id' => 1,
            'email' => 'superadmin@grupoari.com',
            'telefono' => '928301501',
            'extension' => '100',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Administrador - Administración Gran Canaria
        $admin = User::create([
            'nombre' => 'Carmen',
            'apellidos' => 'Santana Medina',
            'empresa_id' => 1,
            'departamento_id' => 1, // Administración
            'centro_id' => 1,
            'email' => 'admin@grupoari.com',
            'telefono' => '928301502',
            'extension' => '101',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Administrador');

        // Gerente - Dirección Tenerife
        $gerente = User::create([
            'nombre' => 'Francisco',
            'apellidos' => 'Hernández Pérez',
            'empresa_id' => 2,
            'departamento_id' => 8, // Dirección
            'centro_id' => 4,
            'email' => 'francisco@grupoari.com',
            'telefono' => '922653201',
            'extension' => '200',
            'password' => Hash::make('password'),
        ]);
        $gerente->assignRole('Gerente');

        // Vendedor - Ventas Gran Canaria Telde
        $vendedor = User::create([
            'nombre' => 'María del Carmen',
            'apellidos' => 'González Suárez',
            'empresa_id' => 1,
            'departamento_id' => 2, // Ventas
            'centro_id' => 2,
            'email' => 'maria@grupoari.com',
            'telefono' => '628445566',
            'extension' => '301',
            'password' => Hash::make('password'),
        ]);
        $vendedor->assignRole('Vendedor');

        // Vendedor - Ventas Tenerife
        $vendedor2 = User::create([
            'nombre' => 'José Antonio',
            'apellidos' => 'Rodríguez Dorta',
            'empresa_id' => 2,
            'departamento_id' => 2, // Ventas
            'centro_id' => 5,
            'email' => 'joseantonio@grupoari.com',
            'telefono' => '622778899',
            'extension' => '302',
            'password' => Hash::make('password'),
        ]);
        $vendedor2->assignRole('Vendedor');

        // Consultor - Lanzarote
        $consultor = User::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Cabrera Betancort',
            'empresa_id' => 3,
            'departamento_id' => 1, // Administración
            'centro_id' => 7,
            'email' => 'pedro@grupoari.com',
            'telefono' => '928812301',
            'extension' => '400',
            'password' => Hash::make('password'),
        ]);
        $consultor->assignRole('Consultor');
    }
}
