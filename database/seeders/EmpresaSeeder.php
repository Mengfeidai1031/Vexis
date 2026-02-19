<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'ARI Motor Gran Canaria S.L.',
            'abreviatura' => 'ARIGC',
            'cif' => 'B35678901',
            'domicilio' => 'Calle Escritor Benito Pérez Galdós 8, Las Palmas de Gran Canaria',
            'codigo_postal' => '35005',
            'telefono' => '928301500',
        ]);

        Empresa::create([
            'nombre' => 'ARI Motor Tenerife S.L.',
            'abreviatura' => 'ARITF',
            'cif' => 'B38765432',
            'domicilio' => 'Avenida de Bélgica 2, Santa Cruz de Tenerife',
            'codigo_postal' => '38008',
            'telefono' => '922653200',
        ]);

        Empresa::create([
            'nombre' => 'ARI Motor Lanzarote S.L.',
            'abreviatura' => 'ARILZ',
            'cif' => 'B35543210',
            'domicilio' => 'Calle Rubicón 15, Arrecife',
            'codigo_postal' => '35500',
            'telefono' => '928812300',
        ]);
    }
}
