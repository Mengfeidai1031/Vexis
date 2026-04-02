<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatalogoPrecio;

class CatalogoPrecioSeeder extends Seeder
{
    public function run(): void
    {
        $catalogo = [
            // ═══════════════════════════════════════════
            // Nissan (marca_id = 1)
            // ═══════════════════════════════════════════
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'Acenta 1.3 DIG-T MHEV','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>32250,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'N-Connecta 1.3 DIG-T MHEV','combustible'=>'Híbrido','potencia_cv'=>158,'precio_base'=>36450,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Qashqai','version'=>'Tekna e-POWER','combustible'=>'Híbrido','potencia_cv'=>190,'precio_base'=>42350,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'X-Trail','version'=>'Acenta e-POWER','combustible'=>'Híbrido','potencia_cv'=>204,'precio_base'=>42100,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'X-Trail','version'=>'Tekna e-4ORCE','combustible'=>'Híbrido','potencia_cv'=>213,'precio_base'=>51500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Juke','version'=>'Acenta DIG-T','combustible'=>'Gasolina','potencia_cv'=>114,'precio_base'=>24600,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Juke','version'=>'N-Connecta Hybrid','combustible'=>'Híbrido','potencia_cv'=>143,'precio_base'=>31750,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Ariya','version'=>'Advance 63kWh','combustible'=>'Eléctrico','potencia_cv'=>218,'precio_base'=>43400,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Ariya','version'=>'Evolve+ 87kWh e-4ORCE','combustible'=>'Eléctrico','potencia_cv'=>306,'precio_base'=>57900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'LEAF','version'=>'Acenta 40kWh','combustible'=>'Eléctrico','potencia_cv'=>150,'precio_base'=>35400,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>1,'modelo'=>'Townstar','version'=>'Combi Acenta','combustible'=>'Gasolina','potencia_cv'=>130,'precio_base'=>27800,'precio_oferta'=>null,'anio_modelo'=>2026],

            // ═══════════════════════════════════════════
            // Renault (marca_id = 2)
            // ═══════════════════════════════════════════
            // Clio
            ['marca_id'=>2,'modelo'=>'Clio','version'=>'Equilibre TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>19250,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Clio','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>25400,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Clio','version'=>'Esprit Alpine E-TECH Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>27200,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Captur
            ['marca_id'=>2,'modelo'=>'Captur','version'=>'Equilibre TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>23750,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Captur','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>30500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Captur','version'=>'Esprit Alpine E-TECH Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>32500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Arkana
            ['marca_id'=>2,'modelo'=>'Arkana','version'=>'Equilibre Mild Hybrid 140','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>30900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Arkana','version'=>'Techno E-TECH Full Hybrid 145','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>36500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Arkana','version'=>'Esprit Alpine E-TECH Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>38200,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Austral
            ['marca_id'=>2,'modelo'=>'Austral','version'=>'Equilibre Mild Hybrid','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>33200,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Austral','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>41500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Austral','version'=>'Esprit Alpine E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>44500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Espace
            ['marca_id'=>2,'modelo'=>'Espace','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>44500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Espace','version'=>'Esprit Alpine E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>48500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Scenic E-Tech
            ['marca_id'=>2,'modelo'=>'Scenic','version'=>'Comfort Range 60kWh','combustible'=>'Eléctrico','potencia_cv'=>170,'precio_base'=>40000,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Scenic','version'=>'Techno Long Range 87kWh','combustible'=>'Eléctrico','potencia_cv'=>220,'precio_base'=>48500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Scenic','version'=>'Esprit Alpine Long Range 87kWh','combustible'=>'Eléctrico','potencia_cv'=>220,'precio_base'=>52000,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Megane E-Tech
            ['marca_id'=>2,'modelo'=>'Megane','version'=>'Equilibre EV40 130hp','combustible'=>'Eléctrico','potencia_cv'=>130,'precio_base'=>36500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Megane','version'=>'Techno EV60 220hp','combustible'=>'Eléctrico','potencia_cv'=>220,'precio_base'=>44500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Symbioz
            ['marca_id'=>2,'modelo'=>'Symbioz','version'=>'Techno E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>34900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Symbioz','version'=>'Esprit Alpine E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>145,'precio_base'=>37500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Rafale
            ['marca_id'=>2,'modelo'=>'Rafale','version'=>'Techno E-TECH Full Hybrid 200','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>46000,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Rafale','version'=>'Atelier Alpine E-TECH Full Hybrid','combustible'=>'Híbrido','potencia_cv'=>200,'precio_base'=>52000,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Rafale','version'=>'Atelier Alpine E-TECH 4x4 300','combustible'=>'Híbrido','potencia_cv'=>300,'precio_base'=>58000,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Kangoo
            ['marca_id'=>2,'modelo'=>'Kangoo','version'=>'Combi Equilibre TCe 130','combustible'=>'Gasolina','potencia_cv'=>130,'precio_base'=>28500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Kangoo','version'=>'Combi Techno E-Tech Electric','combustible'=>'Eléctrico','potencia_cv'=>122,'precio_base'=>36500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Trafic
            ['marca_id'=>2,'modelo'=>'Trafic','version'=>'Combi Equilibre dCi 130','combustible'=>'Diésel','potencia_cv'=>130,'precio_base'=>38500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Trafic','version'=>'SpaceClass Techno dCi 170','combustible'=>'Diésel','potencia_cv'=>170,'precio_base'=>48500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Twingo
            ['marca_id'=>2,'modelo'=>'Twingo','version'=>'Equilibre SCe 65','combustible'=>'Gasolina','potencia_cv'=>65,'precio_base'=>14500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Twingo','version'=>'Techno TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>17500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Renault 5
            ['marca_id'=>2,'modelo'=>'Renault 5','version'=>'Five E-Tech Electric 120','combustible'=>'Eléctrico','potencia_cv'=>120,'precio_base'=>25000,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Renault 5','version'=>'Techno E-Tech Electric 150','combustible'=>'Eléctrico','potencia_cv'=>150,'precio_base'=>30000,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Renault 5','version'=>'Roland Garros E-Tech Electric 150','combustible'=>'Eléctrico','potencia_cv'=>150,'precio_base'=>33000,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Renault 4
            ['marca_id'=>2,'modelo'=>'Renault 4','version'=>'E-Tech Electric 120','combustible'=>'Eléctrico','potencia_cv'=>120,'precio_base'=>28500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>2,'modelo'=>'Renault 4','version'=>'Techno E-Tech Electric 150','combustible'=>'Eléctrico','potencia_cv'=>150,'precio_base'=>33000,'precio_oferta'=>null,'anio_modelo'=>2026],

            // ═══════════════════════════════════════════
            // Dacia (marca_id = 3)
            // ═══════════════════════════════════════════
            // Sandero
            ['marca_id'=>3,'modelo'=>'Sandero','version'=>'Essential TCe 90','combustible'=>'Gasolina','potencia_cv'=>91,'precio_base'=>12250,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Sandero','version'=>'Comfort ECO-G 100','combustible'=>'GLP','potencia_cv'=>100,'precio_base'=>14200,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Sandero','version'=>'Stepway Comfort TCe 110','combustible'=>'Gasolina','potencia_cv'=>110,'precio_base'=>16500,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Duster
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Essential TCe 130 4x2','combustible'=>'Gasolina','potencia_cv'=>130,'precio_base'=>20500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Comfort Hybrid 140 4x2','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>24500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Duster','version'=>'Extreme Hybrid 140 4x4','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>28900,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Jogger
            ['marca_id'=>3,'modelo'=>'Jogger','version'=>'Essential TCe 110','combustible'=>'Gasolina','potencia_cv'=>110,'precio_base'=>18500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Jogger','version'=>'Extreme Hybrid 140','combustible'=>'Híbrido','potencia_cv'=>140,'precio_base'=>24900,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Spring
            ['marca_id'=>3,'modelo'=>'Spring','version'=>'Essential Electric','combustible'=>'Eléctrico','potencia_cv'=>65,'precio_base'=>18900,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Spring','version'=>'Extreme Electric','combustible'=>'Eléctrico','potencia_cv'=>65,'precio_base'=>20900,'precio_oferta'=>null,'anio_modelo'=>2026],
            // Bigster
            ['marca_id'=>3,'modelo'=>'Bigster','version'=>'Essential TCe 140','combustible'=>'Gasolina','potencia_cv'=>140,'precio_base'=>24500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Bigster','version'=>'Comfort Hybrid 155','combustible'=>'Híbrido','potencia_cv'=>155,'precio_base'=>28500,'precio_oferta'=>null,'anio_modelo'=>2026],
            ['marca_id'=>3,'modelo'=>'Bigster','version'=>'Extreme Hybrid 155 4x4','combustible'=>'Híbrido','potencia_cv'=>155,'precio_base'=>32500,'precio_oferta'=>null,'anio_modelo'=>2026],
        ];

        foreach ($catalogo as $item) {
            CatalogoPrecio::firstOrCreate(
                ['marca_id' => $item['marca_id'], 'modelo' => $item['modelo'], 'version' => $item['version']],
                [...$item, 'disponible' => true]
            );
        }
    }
}
