<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Importante: El orden importa por las relaciones
        $this->call([
            EmpresaSeeder::class,
            DepartamentoSeeder::class,
            CentroSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            VehiculoSeeder::class,
            MarcaSeeder::class,
            NoticiaSeeder::class,
            FestivoSeeder::class,
            TallerSeeder::class,
            CatalogoPrecioSeeder::class,
            AlmacenSeeder::class,
            DatosEjemploSeeder::class,
        ]);
    }
}