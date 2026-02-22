<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Nissan', 'slug' => 'nissan', 'color' => '#C3002F'],
            ['nombre' => 'Renault', 'slug' => 'renault', 'color' => '#FFCC00'],
            ['nombre' => 'Dacia', 'slug' => 'dacia', 'color' => '#646B52'],
        ];

        foreach ($marcas as $marca) {
            Marca::firstOrCreate(['slug' => $marca['slug']], $marca);
        }
    }
}
