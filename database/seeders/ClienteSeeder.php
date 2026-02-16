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
            'domicilio' => 'Calle Luna 15, Las Palmas',
            'codigo_postal' => '35001',
        ]);

        Cliente::create([
            'nombre' => 'Ana',
            'apellidos' => 'Martín González',
            'empresa_id' => 1,
            'dni' => '23456789B',
            'domicilio' => 'Avenida Sol 42, Telde',
            'codigo_postal' => '35200',
        ]);

        Cliente::create([
            'nombre' => 'Luis',
            'apellidos' => 'Fernández López',
            'empresa_id' => 2,
            'dni' => '34567890C',
            'domicilio' => 'Calle Estrella 8, Santa Cruz',
            'codigo_postal' => '38001',
        ]);

        Cliente::create([
            'nombre' => 'Carmen',
            'apellidos' => 'Sánchez Ruiz',
            'empresa_id' => 1,
            'dni' => '45678901D',
            'domicilio' => 'Plaza Mayor 3, Las Palmas',
            'codigo_postal' => '35003',
        ]);

        Cliente::create([
            'nombre' => 'Miguel',
            'apellidos' => 'García Díaz',
            'empresa_id' => 2,
            'dni' => '56789012E',
            'domicilio' => 'Calle Mar 25, La Laguna',
            'codigo_postal' => '38200',
        ]);
    }
}