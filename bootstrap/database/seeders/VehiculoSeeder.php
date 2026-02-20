<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        // ========== NISSAN (empresa 1 - Gran Canaria) ==========
        Vehiculo::create([
            'chasis' => 'SJNFBNJ11U0123456',
            'modelo' => 'Nissan Qashqai',
            'version' => '1.3 DIG-T 140 N-Connecta',
            'color_externo' => 'Gris Oscuro Metalizado',
            'color_interno' => 'Negro Tekna',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'SJNFCAJ11U0234567',
            'modelo' => 'Nissan Juke',
            'version' => '1.0 DIG-T 114 N-Design',
            'color_externo' => 'Rojo Fuji',
            'color_interno' => 'Negro/Rojo',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'SJNFEAJ11U0345678',
            'modelo' => 'Nissan X-Trail',
            'version' => 'e-POWER 204 Tekna+',
            'color_externo' => 'Blanco Perla',
            'color_interno' => 'Cuero Marrón',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'SJNFHAJ11U0456789',
            'modelo' => 'Nissan Leaf',
            'version' => '40kWh Acenta',
            'color_externo' => 'Azul Magnético',
            'color_interno' => 'Negro',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'SJNFKAJ11U0567890',
            'modelo' => 'Nissan Ariya',
            'version' => '63kWh Evolve',
            'color_externo' => 'Aurora Green',
            'color_interno' => 'Gris Claro',
            'empresa_id' => 1,
        ]);

        // ========== RENAULT (empresa 2 - Tenerife) ==========
        Vehiculo::create([
            'chasis' => 'VF1RFB00X67123456',
            'modelo' => 'Renault Clio',
            'version' => '1.0 TCe 90 Techno',
            'color_externo' => 'Naranja Valencia',
            'color_interno' => 'Negro Carbono',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'VF1HJD40X69234567',
            'modelo' => 'Renault Captur',
            'version' => '1.3 TCe 140 Esprit Alpine',
            'color_externo' => 'Azul Iron / Negro',
            'color_interno' => 'Cuero/Tela Alpine',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'VF1KKDCA067345678',
            'modelo' => 'Renault Austral',
            'version' => 'E-Tech 200 Iconic',
            'color_externo' => 'Gris Schiste',
            'color_interno' => 'Negro/Marrón Nocciola',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'VF1SEAAA067456789',
            'modelo' => 'Renault Arkana',
            'version' => 'E-Tech 145 R.S. Line',
            'color_externo' => 'Rojo Flamme',
            'color_interno' => 'Negro R.S. Line',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'VF1BCBZZ067567890',
            'modelo' => 'Renault Mégane E-Tech',
            'version' => 'EV60 Iconic',
            'color_externo' => 'Verde Rafale',
            'color_interno' => 'Gris Reciclado',
            'empresa_id' => 2,
        ]);

        // ========== DACIA (empresa 3 - Lanzarote + mixto) ==========
        Vehiculo::create([
            'chasis' => 'UU1HSDAAG67123456',
            'modelo' => 'Dacia Sandero',
            'version' => '1.0 TCe 90 Comfort',
            'color_externo' => 'Azul Iron',
            'color_interno' => 'Gris Oscuro',
            'empresa_id' => 3,
        ]);

        Vehiculo::create([
            'chasis' => 'UU1KSDKAG67234567',
            'modelo' => 'Dacia Sandero Stepway',
            'version' => '1.0 TCe 110 Extreme',
            'color_externo' => 'Marrón Terracota',
            'color_interno' => 'Negro/Cobre',
            'empresa_id' => 3,
        ]);

        Vehiculo::create([
            'chasis' => 'UU1HJEDAG67345678',
            'modelo' => 'Dacia Duster',
            'version' => '1.3 TCe 150 4x2 Journey',
            'color_externo' => 'Verde Cedro',
            'color_interno' => 'Negro Journey',
            'empresa_id' => 3,
        ]);

        Vehiculo::create([
            'chasis' => 'UU1HJEDAG67456789',
            'modelo' => 'Dacia Duster',
            'version' => '1.3 TCe 150 4x4 Extreme',
            'color_externo' => 'Beige Duna',
            'color_interno' => 'Negro/Cobre Extreme',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'UU1LJDAAG67567890',
            'modelo' => 'Dacia Jogger',
            'version' => 'Hybrid 140 Extreme 7 plazas',
            'color_externo' => 'Gris Moonstone',
            'color_interno' => 'Negro 7 plazas',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'UU1LJDAAG67678901',
            'modelo' => 'Dacia Spring',
            'version' => 'Electric 65 Extreme',
            'color_externo' => 'Azul Rayo',
            'color_interno' => 'Negro/Azul',
            'empresa_id' => 1,
        ]);

        Vehiculo::create([
            'chasis' => 'SJNFBNJ11U0678901',
            'modelo' => 'Nissan Townstar',
            'version' => '1.3 DIG-T 130 Acenta',
            'color_externo' => 'Blanco Glaciar',
            'color_interno' => 'Gris',
            'empresa_id' => 2,
        ]);

        Vehiculo::create([
            'chasis' => 'VF1KGAAA067678901',
            'modelo' => 'Renault Scenic E-Tech',
            'version' => 'EV87 Iconic',
            'color_externo' => 'Azul Rafale',
            'color_interno' => 'Gris Reciclado Iconic',
            'empresa_id' => 1,
        ]);
    }
}
