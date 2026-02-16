<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        Vehiculo::create([
            'chasis' => 'WVWZZZ1JZXW123456',
            'modelo' => 'Volkswagen Golf',
            'version' => 'GTI 2.0 TSI DSG',
            'color_externo' => 'Azul Atlántico',
            'color_interno' => 'Negro Titanio',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'WBA1A5C50JV123456',
            'modelo' => 'BMW Serie 3',
            'version' => '320d M Sport',
            'color_externo' => 'Negro Zafiro',
            'color_interno' => 'Cuero Marrón Dakota',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'WBAVC71070F123456',
            'modelo' => 'BMW X5',
            'version' => 'xDrive30d M Sport',
            'color_externo' => 'Blanco Mineral',
            'color_interno' => 'Cuero Negro Vernasca',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'WAUZZZ8V7KA123456',
            'modelo' => 'Audi A4',
            'version' => '2.0 TDI S Line',
            'color_externo' => 'Gris Nardo',
            'color_interno' => 'Negro',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'WDD2130421A123456',
            'modelo' => 'Mercedes-Benz Clase C',
            'version' => 'C220d AMG Line',
            'color_externo' => 'Plata Iridio',
            'color_interno' => 'Negro ARTICO',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'WVWZZZ3CZHE123456',
            'modelo' => 'Volkswagen Tiguan',
            'version' => '2.0 TDI 4Motion R-Line',
            'color_externo' => 'Gris Pirita',
            'color_interno' => 'Negro Titán',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'WAUZZZ4G8KN123456',
            'modelo' => 'Audi Q5',
            'version' => '40 TDI Quattro S Line',
            'color_externo' => 'Azul Navarra',
            'color_interno' => 'Negro',
            'empresa_id' => 2,
        ]);
    }
}