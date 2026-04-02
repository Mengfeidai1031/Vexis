<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;
use App\Models\Marca;

class VehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $nissan = Marca::where('slug', 'nissan')->first()?->id;
        $renault = Marca::where('slug', 'renault')->first()?->id;
        $dacia = Marca::where('slug', 'dacia')->first()?->id;

        $vehiculos = [
            // ========== NISSAN ==========
            ['chasis'=>'SJNFBNJ11U0123456','matricula'=>'4521 NKL','modelo'=>'Qashqai','version'=>'Acenta 1.3 DIG-T MHEV','color_externo'=>'Gris Oscuro Metalizado','color_interno'=>'Negro Tekna','empresa_id'=>1,'marca_id'=>$nissan],
            ['chasis'=>'SJNFCAJ11U0234567','matricula'=>'4522 NKL','modelo'=>'Qashqai','version'=>'Tekna e-POWER','color_externo'=>'Blanco Perla','color_interno'=>'Cuero Marrón','empresa_id'=>1,'marca_id'=>$nissan],
            ['chasis'=>'SJNFEAJ11U0345678','matricula'=>'4523 NKL','modelo'=>'Juke','version'=>'Acenta DIG-T','color_externo'=>'Rojo Fuji','color_interno'=>'Negro/Rojo','empresa_id'=>1,'marca_id'=>$nissan],
            ['chasis'=>'SJNFHAJ11U0456789','matricula'=>'4524 NKL','modelo'=>'X-Trail','version'=>'Acenta e-POWER','color_externo'=>'Azul Magnético','color_interno'=>'Negro','empresa_id'=>1,'marca_id'=>$nissan],
            ['chasis'=>'SJNFKAJ11U0567890','matricula'=>'4525 NKL','modelo'=>'Ariya','version'=>'Advance 63kWh','color_externo'=>'Aurora Green','color_interno'=>'Gris Claro','empresa_id'=>1,'marca_id'=>$nissan],
            ['chasis'=>'SJNFBNJ11U0678901','matricula'=>'4526 NKL','modelo'=>'LEAF','version'=>'Acenta 40kWh','color_externo'=>'Blanco Glaciar','color_interno'=>'Negro','empresa_id'=>2,'marca_id'=>$nissan],

            // ========== RENAULT ==========
            ['chasis'=>'VF1RFB00X67123456','matricula'=>'4527 NKL','modelo'=>'Clio','version'=>'Techno E-TECH Full Hybrid','color_externo'=>'Naranja Valencia','color_interno'=>'Negro Carbono','empresa_id'=>2,'marca_id'=>$renault],
            ['chasis'=>'VF1HJD40X69234567','matricula'=>'4528 NKL','modelo'=>'Captur','version'=>'Techno E-TECH Full Hybrid','color_externo'=>'Azul Iron / Negro','color_interno'=>'Cuero/Tela Alpine','empresa_id'=>2,'marca_id'=>$renault],
            ['chasis'=>'VF1KKDCA067345678','matricula'=>'4529 NKL','modelo'=>'Austral','version'=>'Techno E-TECH Full Hybrid','color_externo'=>'Gris Schiste','color_interno'=>'Negro/Marrón Nocciola','empresa_id'=>2,'marca_id'=>$renault],
            ['chasis'=>'VF1SEAAA067456789','matricula'=>'4530 NKL','modelo'=>'Arkana','version'=>'Techno E-TECH Full Hybrid 145','color_externo'=>'Rojo Flamme','color_interno'=>'Negro R.S. Line','empresa_id'=>2,'marca_id'=>$renault],
            ['chasis'=>'VF1BCBZZ067567890','matricula'=>'4531 NKL','modelo'=>'Scenic','version'=>'Techno Long Range 87kWh','color_externo'=>'Verde Rafale','color_interno'=>'Gris Reciclado','empresa_id'=>1,'marca_id'=>$renault],
            ['chasis'=>'VF1KGAAA067678901','matricula'=>'4532 NKL','modelo'=>'Rafale','version'=>'Techno E-TECH Full Hybrid 200','color_externo'=>'Azul Rafale','color_interno'=>'Cuero Iconic','empresa_id'=>1,'marca_id'=>$renault],

            // ========== DACIA ==========
            ['chasis'=>'UU1HSDAAG67123456','matricula'=>'4533 NKL','modelo'=>'Sandero','version'=>'Essential TCe 90','color_externo'=>'Azul Iron','color_interno'=>'Gris Oscuro','empresa_id'=>3,'marca_id'=>$dacia],
            ['chasis'=>'UU1KSDKAG67234567','matricula'=>'4534 NKL','modelo'=>'Sandero','version'=>'Stepway Comfort TCe 110','color_externo'=>'Marrón Terracota','color_interno'=>'Negro/Cobre','empresa_id'=>3,'marca_id'=>$dacia],
            ['chasis'=>'UU1HJEDAG67345678','matricula'=>'4535 NKL','modelo'=>'Duster','version'=>'Comfort Hybrid 140 4x2','color_externo'=>'Verde Cedro','color_interno'=>'Negro Journey','empresa_id'=>3,'marca_id'=>$dacia],
            ['chasis'=>'UU1HJEDAG67456789','matricula'=>'4536 NKL','modelo'=>'Duster','version'=>'Extreme Hybrid 140 4x4','color_externo'=>'Beige Duna','color_interno'=>'Negro/Cobre Extreme','empresa_id'=>1,'marca_id'=>$dacia],
            ['chasis'=>'UU1LJDAAG67567890','matricula'=>'4537 NKL','modelo'=>'Jogger','version'=>'Extreme Hybrid 140','color_externo'=>'Gris Moonstone','color_interno'=>'Negro','empresa_id'=>2,'marca_id'=>$dacia],
            ['chasis'=>'UU1LJDAAG67678901','matricula'=>'4538 NKL','modelo'=>'Spring','version'=>'Extreme Electric','color_externo'=>'Azul Rayo','color_interno'=>'Negro/Azul','empresa_id'=>1,'marca_id'=>$dacia],
        ];

        foreach ($vehiculos as $v) {
            Vehiculo::firstOrCreate(
                ['chasis' => $v['chasis']],
                $v
            );
        }
    }
}
