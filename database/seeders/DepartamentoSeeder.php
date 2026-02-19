<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['nombre' => 'Administración', 'abreviatura' => 'ADMIN'],
            ['nombre' => 'Ventas', 'abreviatura' => 'VENTAS'],
            ['nombre' => 'Posventa', 'abreviatura' => 'POSV'],
            ['nombre' => 'Recambios', 'abreviatura' => 'RECAMB'],
            ['nombre' => 'Taller', 'abreviatura' => 'TALLER'],
            ['nombre' => 'Recursos Humanos', 'abreviatura' => 'RRHH'],
            ['nombre' => 'Informática', 'abreviatura' => 'IT'],
            ['nombre' => 'Dirección', 'abreviatura' => 'DIR'],
            ['nombre' => 'Marketing', 'abreviatura' => 'MKT'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}
