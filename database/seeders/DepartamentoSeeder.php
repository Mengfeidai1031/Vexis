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
            ['nombre' => 'Compras', 'abreviatura' => 'COMPRAS'],
            ['nombre' => 'Recursos Humanos', 'abreviatura' => 'RRHH'],
            ['nombre' => 'Informática', 'abreviatura' => 'IT'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}