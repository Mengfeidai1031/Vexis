<?php

namespace Database\Seeders;

use App\Models\Festivo;
use Illuminate\Database\Seeder;

class FestivoSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([2024, 2025, 2026] as $anio) {
            $this->seedAnio($anio);
        }
    }

    private function seedAnio(int $anio): void
    {
        $nacionales = [
            ['nombre' => 'Año Nuevo', 'fecha' => "$anio-01-01"],
            ['nombre' => 'Epifanía del Señor', 'fecha' => "$anio-01-06"],
            ['nombre' => 'Viernes Santo', 'fecha' => $anio === 2024 ? "$anio-03-29" : ($anio === 2025 ? "$anio-04-18" : "$anio-04-03")],
            ['nombre' => 'Día del Trabajo', 'fecha' => "$anio-05-01"],
            ['nombre' => 'Asunción de la Virgen', 'fecha' => "$anio-08-15"],
            ['nombre' => 'Fiesta Nacional de España', 'fecha' => "$anio-10-12"],
            ['nombre' => 'Todos los Santos', 'fecha' => "$anio-11-01"],
            ['nombre' => 'Día de la Constitución', 'fecha' => "$anio-12-06"],
            ['nombre' => 'Inmaculada Concepción', 'fecha' => "$anio-12-08"],
            ['nombre' => 'Navidad', 'fecha' => "$anio-12-25"],
        ];
        foreach ($nacionales as $f) {
            Festivo::firstOrCreate(
                ['fecha' => $f['fecha'], 'ambito' => 'nacional', 'municipio' => null],
                [...$f, 'ambito' => 'nacional', 'municipio' => null, 'anio' => $anio]
            );
        }

        Festivo::firstOrCreate(
            ['fecha' => "$anio-05-30", 'ambito' => 'autonomico', 'municipio' => null],
            ['nombre' => 'Día de Canarias', 'fecha' => "$anio-05-30", 'ambito' => 'autonomico', 'municipio' => null, 'anio' => $anio]
        );

        $locales = [
            ['nombre' => 'Fiesta de San Juan', 'fecha' => "$anio-06-24", 'municipio' => 'Las Palmas de Gran Canaria'],
            ['nombre' => 'Nuestra Señora del Pino', 'fecha' => "$anio-09-08", 'municipio' => 'Las Palmas de Gran Canaria'],
            ['nombre' => 'San Antonio Abad', 'fecha' => "$anio-01-17", 'municipio' => 'Arucas'],
            ['nombre' => 'San Juan Bautista', 'fecha' => "$anio-06-24", 'municipio' => 'Telde'],
            ['nombre' => 'Virgen del Pino', 'fecha' => "$anio-09-08", 'municipio' => 'Teror'],
            ['nombre' => 'Virgen de la Candelaria', 'fecha' => "$anio-02-02", 'municipio' => 'Candelaria'],
            ['nombre' => 'Fiesta de la Cruz', 'fecha' => "$anio-05-03", 'municipio' => 'Santa Cruz de Tenerife'],
            ['nombre' => 'San Andrés', 'fecha' => "$anio-11-30", 'municipio' => 'San Cristóbal de La Laguna'],
            ['nombre' => 'San Ginés', 'fecha' => "$anio-08-25", 'municipio' => 'Arrecife'],
            ['nombre' => 'Nuestra Señora del Carmen', 'fecha' => "$anio-07-16", 'municipio' => 'Teguise'],
            ['nombre' => 'Nuestra Señora del Rosario', 'fecha' => "$anio-10-07", 'municipio' => 'Puerto del Rosario'],
        ];
        foreach ($locales as $f) {
            Festivo::firstOrCreate(
                ['fecha' => $f['fecha'], 'municipio' => $f['municipio']],
                [...$f, 'ambito' => 'local', 'anio' => $anio]
            );
        }
    }
}
