<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centro;

class CentroSeeder extends Seeder
{
    public function run(): void
    {
        // Centros de ARI Motor Gran Canaria (empresa_id: 1)
        Centro::create([
            'nombre' => 'Concesionario Las Palmas - Miller Bajo',
            'empresa_id' => 1,
            'direccion' => 'Calle Escritor Benito Pérez Galdós 8',
            'provincia' => 'Las Palmas',
            'municipio' => 'Las Palmas de Gran Canaria',
        ]);

        Centro::create([
            'nombre' => 'Concesionario Telde',
            'empresa_id' => 1,
            'direccion' => 'Polígono Industrial de Salinetas, Parcela 12',
            'provincia' => 'Las Palmas',
            'municipio' => 'Telde',
        ]);

        Centro::create([
            'nombre' => 'Concesionario Vecindario',
            'empresa_id' => 1,
            'direccion' => 'Avenida del Atlántico 50',
            'provincia' => 'Las Palmas',
            'municipio' => 'Santa Lucía de Tirajana',
        ]);

        // Centros de ARI Motor Tenerife (empresa_id: 2)
        Centro::create([
            'nombre' => 'Concesionario Santa Cruz',
            'empresa_id' => 2,
            'direccion' => 'Avenida de Bélgica 2',
            'provincia' => 'Santa Cruz de Tenerife',
            'municipio' => 'Santa Cruz de Tenerife',
        ]);

        Centro::create([
            'nombre' => 'Concesionario La Laguna',
            'empresa_id' => 2,
            'direccion' => 'Carretera General del Norte, Km 4.5',
            'provincia' => 'Santa Cruz de Tenerife',
            'municipio' => 'San Cristóbal de La Laguna',
        ]);

        Centro::create([
            'nombre' => 'Concesionario Sur - Granadilla',
            'empresa_id' => 2,
            'direccion' => 'Polígono Industrial de Granadilla, Nave 7',
            'provincia' => 'Santa Cruz de Tenerife',
            'municipio' => 'Granadilla de Abona',
        ]);

        // Centro de ARI Motor Lanzarote (empresa_id: 3)
        Centro::create([
            'nombre' => 'Concesionario Arrecife',
            'empresa_id' => 3,
            'direccion' => 'Calle Rubicón 15',
            'provincia' => 'Las Palmas',
            'municipio' => 'Arrecife',
        ]);
    }
}
