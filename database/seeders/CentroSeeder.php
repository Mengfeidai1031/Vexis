<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centro;

class CentroSeeder extends Seeder
{
    public function run(): void
    {
        // Centros para Empresa ID 1 (Grupo ARI S.L.)
        Centro::create([
            'nombre' => 'Centro Las Palmas',
            'empresa_id' => 1,
            'direccion' => 'Calle Principal 123',
            'provincia' => 'Las Palmas',
            'municipio' => 'Las Palmas de Gran Canaria',
        ]);

        Centro::create([
            'nombre' => 'Centro Telde',
            'empresa_id' => 1,
            'direccion' => 'Avenida Industrial 45',
            'provincia' => 'Las Palmas',
            'municipio' => 'Telde',
        ]);

        // Centros para Empresa ID 2 (ARI Canarias S.A.)
        Centro::create([
            'nombre' => 'Centro Santa Cruz',
            'empresa_id' => 2,
            'direccion' => 'Avenida Marítima 456',
            'provincia' => 'Santa Cruz de Tenerife',
            'municipio' => 'Santa Cruz de Tenerife',
        ]);
    }
}