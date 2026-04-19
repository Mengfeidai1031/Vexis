<?php

namespace Database\Seeders;

use App\Models\TipoCliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TipoClienteSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Particular',   'descripcion' => 'Cliente particular persona física.', 'color' => '#33AADD'],
            ['nombre' => 'Empresa',      'descripcion' => 'Cliente empresa / persona jurídica.', 'color' => '#2E86DE'],
            ['nombre' => 'Autónomo',     'descripcion' => 'Trabajador autónomo.', 'color' => '#F39C12'],
            ['nombre' => 'Flota',        'descripcion' => 'Cliente con flota de vehículos.', 'color' => '#8E44AD'],
            ['nombre' => 'Administración Pública', 'descripcion' => 'Organismo público o administración.', 'color' => '#16A085'],
            ['nombre' => 'VIP',          'descripcion' => 'Cliente VIP con condiciones especiales.', 'color' => '#C0392B'],
        ];

        foreach ($tipos as $t) {
            TipoCliente::firstOrCreate(
                ['slug' => Str::slug($t['nombre'])],
                array_merge($t, ['slug' => Str::slug($t['nombre']), 'activo' => true])
            );
        }
    }
}
