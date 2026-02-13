<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'Grupo ARI S.L.',
            'abreviatura' => 'GARI',
            'cif' => 'B12345678',
            'domicilio' => 'Calle Principal 123, Las Palmas',
            'telefono' => '928123456',
        ]);

        Empresa::create([
            'nombre' => 'ARI Canarias S.A.',
            'abreviatura' => 'ARIC',
            'cif' => 'A87654321',
            'domicilio' => 'Avenida Marítima 456, Tenerife',
            'telefono' => '922654321',
        ]);
    }
}