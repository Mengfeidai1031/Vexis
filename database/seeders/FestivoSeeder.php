<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Festivo;

class FestivoSeeder extends Seeder
{
    public function run(): void
    {
        $anio = 2026;

        // Festivos nacionales
        $nacionales = [
            ['nombre' => 'Año Nuevo', 'fecha' => "$anio-01-01"],
            ['nombre' => 'Epifanía del Señor', 'fecha' => "$anio-01-06"],
            ['nombre' => 'Viernes Santo', 'fecha' => "$anio-04-03"],
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

        // Festivos autonómicos de Canarias
        $autonomicos = [
            ['nombre' => 'Día de Canarias', 'fecha' => "$anio-05-30"],
        ];

        foreach ($autonomicos as $f) {
            Festivo::firstOrCreate(
                ['fecha' => $f['fecha'], 'ambito' => 'autonomico', 'municipio' => null],
                [...$f, 'ambito' => 'autonomico', 'municipio' => null, 'anio' => $anio]
            );
        }

        // Festivos locales por municipios principales de Canarias
        $locales = [
            // Gran Canaria
            ['nombre' => 'Fiesta de San Juan', 'fecha' => "$anio-06-24", 'municipio' => 'Las Palmas de Gran Canaria'],
            ['nombre' => 'Nuestra Señora del Pino', 'fecha' => "$anio-09-08", 'municipio' => 'Las Palmas de Gran Canaria'],
            ['nombre' => 'San Nicolás de Tolentino', 'fecha' => "$anio-09-10", 'municipio' => 'La Aldea de San Nicolás'],
            ['nombre' => 'Virgen del Rosario', 'fecha' => "$anio-10-07", 'municipio' => 'Agüimes'],
            ['nombre' => 'San Antonio Abad', 'fecha' => "$anio-01-17", 'municipio' => 'Arucas'],
            ['nombre' => 'San Juan Bautista', 'fecha' => "$anio-06-24", 'municipio' => 'Telde'],
            ['nombre' => 'Fiesta del Carmen', 'fecha' => "$anio-07-16", 'municipio' => 'Mogán'],
            ['nombre' => 'Virgen del Pino', 'fecha' => "$anio-09-08", 'municipio' => 'Teror'],
            ['nombre' => 'San Bartolomé de Tirajana', 'fecha' => "$anio-08-24", 'municipio' => 'San Bartolomé de Tirajana'],
            ['nombre' => 'Santa Lucía', 'fecha' => "$anio-12-13", 'municipio' => 'Santa Lucía de Tirajana'],
            ['nombre' => 'San Mateo', 'fecha' => "$anio-09-21", 'municipio' => 'Vega de San Mateo'],
            ['nombre' => 'Fiesta de San Fernando', 'fecha' => "$anio-05-30", 'municipio' => 'Maspalomas'],
            // Tenerife
            ['nombre' => 'Virgen de la Candelaria', 'fecha' => "$anio-02-02", 'municipio' => 'Candelaria'],
            ['nombre' => 'Fiesta de la Cruz', 'fecha' => "$anio-05-03", 'municipio' => 'Santa Cruz de Tenerife'],
            ['nombre' => 'Santiago Apóstol', 'fecha' => "$anio-07-25", 'municipio' => 'Santa Cruz de Tenerife'],
            ['nombre' => 'Virgen del Carmen', 'fecha' => "$anio-07-16", 'municipio' => 'Puerto de la Cruz'],
            ['nombre' => 'San Andrés', 'fecha' => "$anio-11-30", 'municipio' => 'San Cristóbal de La Laguna'],
            ['nombre' => 'Cristo de La Laguna', 'fecha' => "$anio-09-14", 'municipio' => 'San Cristóbal de La Laguna'],
            ['nombre' => 'San Marcos', 'fecha' => "$anio-04-25", 'municipio' => 'Icod de los Vinos'],
            ['nombre' => 'Virgen de los Remedios', 'fecha' => "$anio-09-08", 'municipio' => 'La Laguna'],
            ['nombre' => 'San Isidro Labrador', 'fecha' => "$anio-05-15", 'municipio' => 'Granadilla de Abona'],
            // Lanzarote
            ['nombre' => 'San Ginés', 'fecha' => "$anio-08-25", 'municipio' => 'Arrecife'],
            ['nombre' => 'Virgen de los Dolores', 'fecha' => "$anio-09-15", 'municipio' => 'Tinajo'],
            ['nombre' => 'Nuestra Señora del Carmen', 'fecha' => "$anio-07-16", 'municipio' => 'Teguise'],
            // Fuerteventura
            ['nombre' => 'Virgen de la Peña', 'fecha' => "$anio-09-15", 'municipio' => 'Betancuria'],
            ['nombre' => 'Nuestra Señora del Rosario', 'fecha' => "$anio-10-07", 'municipio' => 'Puerto del Rosario'],
            ['nombre' => 'San Miguel Arcángel', 'fecha' => "$anio-09-29", 'municipio' => 'Tuineje'],
            // La Palma
            ['nombre' => 'Bajada de la Virgen de las Nieves', 'fecha' => "$anio-08-05", 'municipio' => 'Santa Cruz de La Palma'],
            ['nombre' => 'San Andrés', 'fecha' => "$anio-11-30", 'municipio' => 'San Andrés y Sauces'],
            // La Gomera
            ['nombre' => 'Virgen de Guadalupe', 'fecha' => "$anio-10-12", 'municipio' => 'San Sebastián de La Gomera'],
            // El Hierro
            ['nombre' => 'Virgen de los Reyes', 'fecha' => "$anio-04-25", 'municipio' => 'Valverde'],
        ];

        foreach ($locales as $f) {
            Festivo::firstOrCreate(
                ['fecha' => $f['fecha'], 'municipio' => $f['municipio']],
                [...$f, 'ambito' => 'local', 'anio' => $anio]
            );
        }
    }
}
