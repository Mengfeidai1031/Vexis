<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Renault', 'slug' => 'renault', 'color' => '#FFCC00'],
            ['nombre' => 'Dacia', 'slug' => 'dacia', 'color' => '#646B52'],
            ['nombre' => 'Nissan', 'slug' => 'nissan', 'color' => '#C3002F'],
        ];

        foreach ($marcas as $marca) {
            Marca::firstOrCreate(['slug' => $marca['slug']], $marca);
        }
    }
}
