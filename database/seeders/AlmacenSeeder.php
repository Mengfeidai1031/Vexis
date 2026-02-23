<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Almacen;

class AlmacenSeeder extends Seeder
{
    public function run(): void
    {
        $almacenes = [
            [
                'nombre' => 'Almacén Central Las Palmas',
                'codigo' => 'ALC-GC01',
                'domicilio' => 'Pol. Ind. El Sebadal, C/ La Fragata 12',
                'codigo_postal' => '35008',
                'localidad' => 'Las Palmas de Gran Canaria',
                'isla' => 'Gran Canaria',
                'telefono' => '928301020',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Sur Gran Canaria',
                'codigo' => 'ALC-GC02',
                'domicilio' => 'Av. de Tirajana 28, Pol. Ind. San Fernando',
                'codigo_postal' => '35100',
                'localidad' => 'San Bartolomé de Tirajana',
                'isla' => 'Gran Canaria',
                'telefono' => '928762030',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Telde',
                'codigo' => 'ALC-GC03',
                'domicilio' => 'C/ Drago 5, Pol. Ind. Las Rubiesas',
                'codigo_postal' => '35200',
                'localidad' => 'Telde',
                'isla' => 'Gran Canaria',
                'telefono' => '928694050',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Santa Cruz de Tenerife',
                'codigo' => 'ALC-TF01',
                'domicilio' => 'Pol. Ind. Güímar, C/ Los Cascajos 3',
                'codigo_postal' => '38509',
                'localidad' => 'Güímar',
                'isla' => 'Tenerife',
                'telefono' => '922510060',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Sur Tenerife',
                'codigo' => 'ALC-TF02',
                'domicilio' => 'Pol. Ind. Granadilla, Av. Principal 15',
                'codigo_postal' => '38600',
                'localidad' => 'Granadilla de Abona',
                'isla' => 'Tenerife',
                'telefono' => '922773080',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Lanzarote',
                'codigo' => 'ALC-LZ01',
                'domicilio' => 'Pol. Ind. Playa Honda, C/ Berlina 8',
                'codigo_postal' => '35509',
                'localidad' => 'San Bartolomé',
                'isla' => 'Lanzarote',
                'telefono' => '928820090',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
            [
                'nombre' => 'Almacén Fuerteventura',
                'codigo' => 'ALC-FV01',
                'domicilio' => 'Pol. Ind. El Matorral, C/ La Higuera 4',
                'codigo_postal' => '35600',
                'localidad' => 'Puerto del Rosario',
                'isla' => 'Fuerteventura',
                'telefono' => '928530070',
                'empresa_id' => 1,
                'centro_id' => 1,
            ],
        ];

        foreach ($almacenes as $a) {
            Almacen::firstOrCreate(['codigo' => $a['codigo']], $a);
        }
    }
}
