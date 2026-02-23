<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatalogoPrecio;

class CatalogoPrecioSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            // Nissan (marca_id = 1)
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'Acenta 1.3 DIG-T MHEV','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>32250,'precio_oferta'=>29990,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'N-Connecta 1.3 DIG-T MHEV','combustible'=>'Híbrido','potencia_cv'=>158,'precio_base'=>36450,'precio_oferta'=>33990,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'Tekna e-POWER','combustible'=>'Híbrido','potencia_cv'=>190,'precio_base'=>42350,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'X-Trail','version'=>'Acenta e-POWER','combustible'=>'Híbrido','potencia_cv'=>204,'precio_base'=>42100,'precio_oferta'=>39500,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'X-Trail','version'=>'Tekna e-4ORCE','combustible'=>'Híbrido','potencia_cv'=>213,'precio_base'=>51500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Juke','version'=>'Acenta DIG-T','combustible'=>'Gasolina','potencia_cv'=>114,'precio_base'=>24600,'precio_oferta'=>22490,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Juke','version'=>'N-Connecta Hybrid','combustible'=>'Híbrido','potencia_cv'=>143,'precio_base'=>31750,'precio_oferta'=>29500,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Ariya','version'=>'Advance 63kWh','combustible'=>'Eléctrico','potencia_cv'=>218,'precio_base'=>43400,'precio_oferta'=>40990,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Ariya','version'=>'Evolve+ 87kWh e-4ORCE','combustible'=>'Eléctrico','potencia_cv'=>306,'precio_base'=>57900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'LEAF','version'=>'Acenta 40kWh','combustible'=>'Eléctrico','potencia_cv'=>150,'precio_base'=>35400,'precio_oferta'=>32900,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Townstar','version'=>'Combi Acenta','combustible'=>'Gasolina','potencia_cv'=>130,'precio_base'=>27800,'precio_oferta'=>25990,'anio_modelo'=>2026],
            // Renault (marca_id = 2)
            ['marca_id'=>2,'modelo'=>'Clio','version'=>'Equilibre TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>19250,'precio_oferta'=>17490,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Clio','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>25400,'precio_oferta'=>23500,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Captur','version'=>'Equilibre TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>23750,'precio_oferta'=>21990,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Captur','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>30500,'precio_oferta'=>28490,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Austral','version'=>'Equilibre Mild Hybrid','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>33200,'precio_oferta'=>30990,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Austral','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>41500,'precio_oferta'=>38990,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Espace','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>44500,'precio_oferta'=>42000,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Scenic E-Tech','version'=>'Comfort Range 60kWh','combustible'=>'Eléctrico','potencia_cv'=>170,'precio_base'=>40000,'precio_oferta'=>37990,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Scenic E-Tech','version'=>'Techno Long Range 87kWh','combustible'=>'Eléctrico','potencia_cv'=>220,'precio_base'=>48500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Megane E-Tech','version'=>'Equilibre EV40 130hp','combustible'=>'Eléctrico','potencia_cv'=>130,'precio_base'=>36500,'precio_oferta'=>33990,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Symbioz','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>34900,'precio_oferta'=>32990,'anio_modelo'=>2026],
            // Dacia (marca_id = 3)
            ['marca_id'=>3,'modelo'=>'Sandero','version'=>'Essential TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>12250,'precio_oferta'=>11490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Sandero','version'=>'Comfort ECO-G 100','combustible'=>'GLP','potencia_cv'=>100,'precio_base'=>14200,'precio_oferta'=>13490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Sandero Stepway','version'=>'Comfort TCe 110','combustible'=>'Gasolina','potencia_cv'=>110,'precio_base'=>16500,'precio_oferta'=>15490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Essential TCe 130 4x2','combustible'=>'Gasolina','potencia_cv'=>130,'precio_base'=>20500,'precio_oferta'=>19250,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Comfort Hybrid 140 4x2','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>24500,'precio_oferta'=>23490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Extreme Hybrid 140 4x4','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>28900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Jogger','version'=>'Essential TCe 110','combustible'=>'Gasolina','potencia_cv'=>110,'precio_base'=>18500,'precio_oferta'=>17490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Jogger','version'=>'Extreme Hybrid 140','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>24900,'precio_oferta'=>23490,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Spring','version'=>'Essential Electric','combustible'=>'Eléctrico','potencia_cv'=>65,'precio_base'=>18900,'precio_oferta'=>16990,'anio_modelo'=>2026],
        ];

        foreach ($catalogo as $item) {
            CatalogoPrecio::firstOrCreate(
                ['marca_id' => $item['marca_id'], 'modelo' => $item['modelo'], 'version' => $item['version']],
                [...$item, 'disponible' => true]
            );
        }
    }
}
