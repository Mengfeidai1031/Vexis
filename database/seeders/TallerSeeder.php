<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taller;

class TallerSeeder extends Seeder
{
    public function run(): void
    {
        $talleres = [
            ['nombre'=>'Taller Nissan Las Palmas','codigo'=>'TAL-NIS-LP','domicilio'=>'Calle Néstor de la Torre, 18','codigo_postal'=>'35006','localidad'=>'Las Palmas de Gran Canaria','isla'=>'Gran Canaria','telefono'=>'928123456','empresa_id'=>1,'centro_id'=>1,'marca_id'=>1,'capacidad_diaria'=>12],
            ['nombre'=>'Taller Renault Las Palmas','codigo'=>'TAL-REN-LP','domicilio'=>'Avda. de Escaleritas, 54','codigo_postal'=>'35011','localidad'=>'Las Palmas de Gran Canaria','isla'=>'Gran Canaria','telefono'=>'928234567','empresa_id'=>1,'centro_id'=>1,'marca_id'=>2,'capacidad_diaria'=>10],
            ['nombre'=>'Taller Dacia Las Palmas','codigo'=>'TAL-DAC-LP','domicilio'=>'Calle León y Castillo, 200','codigo_postal'=>'35004','localidad'=>'Las Palmas de Gran Canaria','isla'=>'Gran Canaria','telefono'=>'928345678','empresa_id'=>1,'centro_id'=>1,'marca_id'=>3,'capacidad_diaria'=>8],
            ['nombre'=>'Taller Nissan Telde','codigo'=>'TAL-NIS-TL','domicilio'=>'Polígono Industrial de Salinetas','codigo_postal'=>'35214','localidad'=>'Telde','isla'=>'Gran Canaria','telefono'=>'928456789','empresa_id'=>1,'centro_id'=>2,'marca_id'=>1,'capacidad_diaria'=>8],
            ['nombre'=>'Taller Nissan Tenerife','codigo'=>'TAL-NIS-TF','domicilio'=>'Calle San Sebastián, 45','codigo_postal'=>'38003','localidad'=>'Santa Cruz de Tenerife','isla'=>'Tenerife','telefono'=>'922123456','empresa_id'=>1,'centro_id'=>3,'marca_id'=>1,'capacidad_diaria'=>10],
            ['nombre'=>'Taller Renault Tenerife','codigo'=>'TAL-REN-TF','domicilio'=>'Avda. Tres de Mayo, 12','codigo_postal'=>'38005','localidad'=>'Santa Cruz de Tenerife','isla'=>'Tenerife','telefono'=>'922234567','empresa_id'=>1,'centro_id'=>3,'marca_id'=>2,'capacidad_diaria'=>10],
            ['nombre'=>'Taller Nissan Lanzarote','codigo'=>'TAL-NIS-LZ','domicilio'=>'Calle Fajardo, 8','codigo_postal'=>'35500','localidad'=>'Arrecife','isla'=>'Lanzarote','telefono'=>'928567890','empresa_id'=>1,'centro_id'=>4,'marca_id'=>1,'capacidad_diaria'=>6],
            ['nombre'=>'Taller Renault Fuerteventura','codigo'=>'TAL-REN-FV','domicilio'=>'Calle Almirante Lallermand, 3','codigo_postal'=>'35600','localidad'=>'Puerto del Rosario','isla'=>'Fuerteventura','telefono'=>'928678901','empresa_id'=>1,'centro_id'=>5,'marca_id'=>2,'capacidad_diaria'=>6],
        ];

        foreach ($talleres as $t) {
            Taller::firstOrCreate(['codigo' => $t['codigo']], $t);
        }
    }
}
