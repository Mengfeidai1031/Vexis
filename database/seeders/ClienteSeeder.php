<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Rodríguez Pérez',
            'empresa_id' => 1,
            'dni' => '12345678A',
            'email' => 'carlos.rodriguez@ejemplo.com',
            'telefono' => '634001001',
            'domicilio' => 'Calle Luna 15, Las Palmas',
            'codigo_postal' => '35001',
        ]);

        Cliente::create([
            'nombre' => 'Ana',
            'apellidos' => 'Martín González',
            'empresa_id' => 1,
            'dni' => '23456789B',
            'email' => 'ana.martin@ejemplo.com',
            'telefono' => '634002002',
            'domicilio' => 'Avenida Sol 42, Telde',
            'codigo_postal' => '35200',
        ]);

        Cliente::create([
            'nombre' => 'Luis',
            'apellidos' => 'Fernández López',
            'empresa_id' => 2,
            'dni' => '34567890C',
            'email' => 'luis.fernandez@ejemplo.com',
            'telefono' => '634003003',
            'domicilio' => 'Calle Estrella 8, Santa Cruz',
            'codigo_postal' => '38001',
        ]);

        Cliente::create([
            'nombre' => 'Carmen',
            'apellidos' => 'Sánchez Ruiz',
            'empresa_id' => 1,
            'dni' => '45678901D',
            'email' => 'carmen.sanchez@ejemplo.com',
            'telefono' => '634004004',
            'domicilio' => 'Plaza Mayor 3, Las Palmas',
            'codigo_postal' => '35003',
        ]);

        Cliente::create([
            'nombre' => 'Miguel',
            'apellidos' => 'García Díaz',
            'empresa_id' => 2,
            'dni' => '56789012E',
            'email' => 'miguel.garcia@ejemplo.com',
            'telefono' => '634005005',
            'domicilio' => 'Calle Mar 25, La Laguna',
            'codigo_postal' => '38200',
        ]);
    }
}