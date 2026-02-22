<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Noticia;

class NoticiaSeeder extends Seeder
{
    public function run(): void
    {
        $noticias = [
            [
                'titulo' => 'Grupo ARI inaugura nuevo concesionario Nissan en Tenerife',
                'contenido' => 'Grupo ARI ha inaugurado un nuevo punto de venta Nissan en la zona sur de Tenerife, ampliando su presencia en las Islas Canarias. El nuevo concesionario cuenta con una superficie de más de 1.500 m² y ofrece toda la gama de vehículos Nissan, incluyendo los nuevos modelos eléctricos. La inauguración contó con la presencia del director general de Grupo ARI y representantes de Nissan España.',
                'categoria' => 'empresa',
                'destacada' => true,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-02-01 10:00:00',
            ],
            [
                'titulo' => 'Nuevo Renault Scenic E-Tech ya disponible en nuestros concesionarios',
                'contenido' => 'El nuevo Renault Scenic E-Tech 100% eléctrico ya está disponible en todos los concesionarios de Grupo ARI. Con una autonomía de hasta 625 km y un diseño completamente renovado, el Scenic E-Tech representa el compromiso de Renault con la movilidad sostenible. Ven a probarlo y descubre las condiciones especiales de lanzamiento.',
                'categoria' => 'comercial',
                'destacada' => true,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-01-28 09:30:00',
            ],
            [
                'titulo' => 'Jornada de puertas abiertas Dacia: 15 y 16 de febrero',
                'contenido' => 'Los próximos días 15 y 16 de febrero celebramos jornada de puertas abiertas en todos nuestros concesionarios Dacia. Descubre la nueva gama Dacia con ofertas exclusivas y condiciones especiales de financiación. Además, habrá actividades para toda la familia y sorteos entre los asistentes.',
                'categoria' => 'comercial',
                'destacada' => false,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-02-05 11:00:00',
            ],
            [
                'titulo' => 'Actualización del sistema VEXIS: nuevas funcionalidades',
                'contenido' => 'Se ha desplegado una nueva actualización del sistema de gestión VEXIS que incluye mejoras en el módulo de ofertas comerciales, un nuevo panel de notificaciones en tiempo real y optimización del rendimiento general. Todos los usuarios pueden acceder a las nuevas funcionalidades desde hoy.',
                'categoria' => 'tecnologia',
                'destacada' => false,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-02-10 08:00:00',
            ],
            [
                'titulo' => 'Plan de formación 2026: inscripciones abiertas',
                'contenido' => 'Ya están abiertas las inscripciones para el plan de formación 2026 de Grupo ARI. Este año se ofrecen cursos de atención al cliente, ventas consultivas, gestión de taller y nuevas tecnologías de vehículos eléctricos. Los empleados interesados pueden inscribirse a través de su responsable de departamento.',
                'categoria' => 'rrhh',
                'destacada' => false,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-01-20 14:00:00',
            ],
            [
                'titulo' => 'Resultados comerciales enero 2026: crecimiento del 12%',
                'contenido' => 'Grupo ARI cierra enero de 2026 con un incremento del 12% en ventas respecto al mismo periodo del año anterior. Destaca especialmente el rendimiento de la marca Dacia, que ha experimentado un crecimiento del 25% impulsado por los nuevos modelos Jogger y Duster. Enhorabuena a todo el equipo comercial.',
                'categoria' => 'empresa',
                'destacada' => false,
                'autor_id' => 1,
                'fecha_publicacion' => '2026-02-03 16:00:00',
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::firstOrCreate(['titulo' => $noticia['titulo']], $noticia);
        }
    }
}
