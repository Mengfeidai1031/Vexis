<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            DepartamentoSeeder::class,
            CentroSeeder::class,
            RolePermissionSeeder::class,
            TipoClienteSeeder::class,
            MarcaSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            VehiculoSeeder::class,
            CatalogoPrecioSeeder::class,
            NoticiaSeeder::class,
            FestivoSeeder::class,
            TallerSeeder::class,
            AlmacenSeeder::class,
            DatosEjemploSeeder::class,
            VerifactuSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
