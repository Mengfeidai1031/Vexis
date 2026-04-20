<?php

namespace Database\Seeders;

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoticiaSeeder extends Seeder
{
    public function run(): void
    {
        $autorId = User::first()?->id ?? 1;

        $noticias = [
            // 2024
            ['titulo' => 'Grupo DAI apuesta por la electromovilidad en Canarias', 'contenido' => 'En 2024 arrancamos un plan estratégico de electromovilidad para todos los concesionarios del grupo, incorporando infraestructura de carga rápida y ampliando la oferta de vehículos eléctricos e híbridos.', 'categoria' => 'empresa', 'destacada' => true, 'fecha_publicacion' => '2024-02-12 10:00:00'],
            ['titulo' => 'Jornada formativa sobre vehículos eléctricos', 'contenido' => 'Todos los mecánicos del grupo han participado en la jornada formativa especializada en diagnóstico y reparación de vehículos eléctricos y híbridos.', 'categoria' => 'rrhh', 'destacada' => false, 'fecha_publicacion' => '2024-04-18 09:00:00'],
            ['titulo' => 'Renault Scenic E-Tech elegido coche del año', 'contenido' => 'El Renault Scenic E-Tech 100% eléctrico ha sido galardonado como Coche del Año en Europa 2024. Disponible en todos nuestros concesionarios.', 'categoria' => 'comercial', 'destacada' => true, 'fecha_publicacion' => '2024-03-10 11:00:00'],
            ['titulo' => 'Récord de ventas en el primer semestre de 2024', 'contenido' => 'Grupo DAI cierra el primer semestre de 2024 con un récord histórico de ventas, destacando especialmente la gama Dacia y los híbridos Renault E-Tech.', 'categoria' => 'empresa', 'destacada' => false, 'fecha_publicacion' => '2024-07-05 08:30:00'],
            ['titulo' => 'Apertura del nuevo Taller Nissan en Arrecife', 'contenido' => 'Hemos inaugurado el nuevo taller oficial Nissan en Arrecife, Lanzarote, ampliando nuestra cobertura postventa en las islas orientales.', 'categoria' => 'empresa', 'destacada' => true, 'fecha_publicacion' => '2024-09-20 12:00:00'],
            ['titulo' => 'Campaña Black Friday 2024 en Dacia', 'contenido' => 'Del 22 al 30 de noviembre de 2024, campaña especial Black Friday en toda la gama Dacia, con descuentos de hasta 2.000€ y financiación al 0% TAE.', 'categoria' => 'comercial', 'destacada' => false, 'fecha_publicacion' => '2024-11-15 10:00:00'],

            // 2025
            ['titulo' => 'Lanzamiento del nuevo Renault 5 E-Tech eléctrico', 'contenido' => 'El nuevo Renault 5 E-Tech ya está disponible en nuestros concesionarios. Un icono reinventado en formato 100% eléctrico con hasta 400 km de autonomía.', 'categoria' => 'comercial', 'destacada' => true, 'fecha_publicacion' => '2025-01-20 09:30:00'],
            ['titulo' => 'Plan de formación 2025: inscripciones abiertas', 'contenido' => 'Ya están disponibles los cursos del plan de formación 2025, con especial foco en atención al cliente, ventas consultivas y nuevas tecnologías.', 'categoria' => 'rrhh', 'destacada' => false, 'fecha_publicacion' => '2025-02-01 14:00:00'],
            ['titulo' => 'Dacia Bigster llega a Canarias', 'contenido' => 'El nuevo Dacia Bigster, el SUV familiar de 7 plazas, aterriza en nuestros concesionarios con precios desde 24.500€.', 'categoria' => 'comercial', 'destacada' => true, 'fecha_publicacion' => '2025-04-12 10:00:00'],
            ['titulo' => 'Acuerdo con el Cabildo de Tenerife para flota eléctrica', 'contenido' => 'Grupo DAI suministrará la nueva flota 100% eléctrica del Cabildo de Tenerife, compuesta por 40 unidades Renault Megane E-Tech.', 'categoria' => 'empresa', 'destacada' => true, 'fecha_publicacion' => '2025-06-08 11:30:00'],
            ['titulo' => 'Implantación de Verifactu para cumplimiento RD 1007/2023', 'contenido' => 'Con la entrada en vigor del Real Decreto 1007/2023, el sistema VEXIS incorpora el módulo Verifactu para garantizar el cumplimiento normativo en toda la facturación del grupo.', 'categoria' => 'tecnologia', 'destacada' => true, 'fecha_publicacion' => '2025-09-15 09:00:00'],
            ['titulo' => 'Resultados comerciales Q3 2025: +15% en ventas', 'contenido' => 'El grupo cierra el tercer trimestre de 2025 con un crecimiento del 15% respecto al mismo periodo del año anterior.', 'categoria' => 'empresa', 'destacada' => false, 'fecha_publicacion' => '2025-10-10 16:00:00'],

            // 2026
            ['titulo' => 'Grupo DAI inaugura nuevo concesionario Nissan en Tenerife', 'contenido' => 'Nuevo concesionario Nissan en la zona sur de Tenerife con más de 1.500 m² y toda la gama incluyendo eléctricos. Inauguración con Nissan España.', 'categoria' => 'empresa', 'destacada' => true, 'fecha_publicacion' => '2026-02-01 10:00:00'],
            ['titulo' => 'Nuevo Renault Scenic E-Tech disponible', 'contenido' => 'Renault Scenic E-Tech 100% eléctrico disponible en todos los concesionarios con hasta 625 km de autonomía.', 'categoria' => 'comercial', 'destacada' => true, 'fecha_publicacion' => '2026-01-28 09:30:00'],
            ['titulo' => 'Jornada puertas abiertas Dacia 15-16 febrero', 'contenido' => 'Jornada de puertas abiertas en todos los concesionarios Dacia con ofertas exclusivas y condiciones especiales de financiación.', 'categoria' => 'comercial', 'destacada' => false, 'fecha_publicacion' => '2026-02-05 11:00:00'],
            ['titulo' => 'Actualización del sistema VEXIS: nuevas funcionalidades', 'contenido' => 'Nueva actualización VEXIS con mejoras en ofertas comerciales, panel de notificaciones y optimización general de rendimiento.', 'categoria' => 'tecnologia', 'destacada' => false, 'fecha_publicacion' => '2026-02-10 08:00:00'],
            ['titulo' => 'Plan de formación 2026: inscripciones abiertas', 'contenido' => 'Cursos 2026: atención al cliente, ventas consultivas, gestión de taller y tecnologías de vehículos eléctricos.', 'categoria' => 'rrhh', 'destacada' => false, 'fecha_publicacion' => '2026-01-20 14:00:00'],
            ['titulo' => 'Resultados comerciales enero 2026: crecimiento del 12%', 'contenido' => 'Incremento del 12% en ventas respecto al año anterior, destacando Dacia con +25% gracias a Jogger y Duster.', 'categoria' => 'empresa', 'destacada' => false, 'fecha_publicacion' => '2026-02-03 16:00:00'],
            ['titulo' => 'Nuevo módulo Dataxis para análisis predictivo', 'contenido' => 'El módulo Dataxis se actualiza con capacidades de análisis predictivo basado en el histórico de ventas y taller.', 'categoria' => 'tecnologia', 'destacada' => false, 'fecha_publicacion' => '2026-03-15 10:00:00'],
        ];

        foreach ($noticias as $noticia) {
            Noticia::firstOrCreate(
                ['titulo' => $noticia['titulo']],
                array_merge($noticia, ['autor_id' => $autorId, 'publicada' => true])
            );
        }
    }
}
