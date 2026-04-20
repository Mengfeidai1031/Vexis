<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\TipoCliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $particular = TipoCliente::where('slug', 'particular')->value('id');
        $empresa = TipoCliente::where('slug', 'empresa')->value('id');
        $autonomo = TipoCliente::where('slug', 'autonomo')->value('id');
        $flota = TipoCliente::where('slug', 'flota')->value('id');
        $admin = TipoCliente::where('slug', 'administracion-publica')->value('id');
        $vip = TipoCliente::where('slug', 'vip')->value('id');

        $clientes = [
            // Gran Canaria (empresa 1)
            ['nombre' => 'Carlos', 'apellidos' => 'Rodríguez Vega', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '42876543A', 'email' => 'carlos.rodriguez@gmail.com', 'tel' => '628112233', 'dom' => 'Calle Triana 52, Las Palmas de Gran Canaria', 'cp' => '35002'],
            ['nombre' => 'Ana Belén', 'apellidos' => 'Martín Suárez', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '43765432B', 'email' => 'anabelen.martin@hotmail.com', 'tel' => '629334455', 'dom' => 'Calle Secretario Artiles 24, Telde', 'cp' => '35200'],
            ['nombre' => 'Miguel Ángel', 'apellidos' => 'Sánchez Ojeda', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '78654321C', 'email' => 'miguelangel.sanchez@yahoo.es', 'tel' => '630556677', 'dom' => 'Avenida de Canarias 128, Vecindario', 'cp' => '35110'],
            ['nombre' => 'Luisa', 'apellidos' => 'Peñate Cabrera', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '44321098D', 'email' => 'luisa.penate@gmail.com', 'tel' => '631778899', 'dom' => 'Calle León y Castillo 200, Las Palmas de Gran Canaria', 'cp' => '35004'],
            ['nombre' => 'Construcciones Tirajana', 'apellidos' => 'S.L.', 'empresa_id' => 1, 'tipo' => $empresa, 'dni' => 'B35111222', 'email' => 'admin@construcciones-tirajana.com', 'tel' => '928111222', 'dom' => 'Pol. Ind. Arinaga, C/ Central 5', 'cp' => '35118'],
            ['nombre' => 'Restauración El Puerto', 'apellidos' => 'S.L.', 'empresa_id' => 1, 'tipo' => $empresa, 'dni' => 'B35334455', 'email' => 'contabilidad@elpuerto.es', 'tel' => '928334455', 'dom' => 'Paseo Las Canteras 50, Las Palmas de Gran Canaria', 'cp' => '35007'],
            ['nombre' => 'Francisco Javier', 'apellidos' => 'Montesdeoca Ruiz', 'empresa_id' => 1, 'tipo' => $autonomo, 'dni' => '45098765K', 'email' => 'fj.montesdeoca@gmail.com', 'tel' => '638112233', 'dom' => 'Calle Obispo Codina 18, Las Palmas', 'cp' => '35001'],
            ['nombre' => 'Transportes Atlántico', 'apellidos' => 'S.A.', 'empresa_id' => 1, 'tipo' => $flota, 'dni' => 'A35987654', 'email' => 'flotas@transportes-atlantico.com', 'tel' => '928556677', 'dom' => 'Pol. Ind. Jinámar, Nave 45, Telde', 'cp' => '35220'],
            ['nombre' => 'Ayuntamiento', 'apellidos' => 'de Mogán', 'empresa_id' => 1, 'tipo' => $admin, 'dni' => 'P3501400B', 'email' => 'contratacion@mogan.es', 'tel' => '928569101', 'dom' => 'Avenida de Mogán s/n', 'cp' => '35140'],
            ['nombre' => 'Beatriz', 'apellidos' => 'Cabrera Robayna', 'empresa_id' => 1, 'tipo' => $vip, 'dni' => '44654321J', 'email' => 'beatriz.cabrera@gmail.com', 'tel' => '637990022', 'dom' => 'Calle Agustín Millares 15, Arucas', 'cp' => '35400'],
            ['nombre' => 'Juan Miguel', 'apellidos' => 'Quintana Guerra', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '46112233M', 'email' => 'juanmi.quintana@gmail.com', 'tel' => '639112244', 'dom' => 'Calle Tomás Morales 30, Las Palmas', 'cp' => '35003'],
            ['nombre' => 'Sara', 'apellidos' => 'Ojeda Pérez', 'empresa_id' => 1, 'tipo' => $particular, 'dni' => '47223344N', 'email' => 'sara.ojeda@hotmail.com', 'tel' => '640223355', 'dom' => 'Avenida Mesa y López 45, Las Palmas', 'cp' => '35010'],

            // Tenerife (empresa 2)
            ['nombre' => 'Fernando', 'apellidos' => 'García Dorta', 'empresa_id' => 2, 'tipo' => $particular, 'dni' => '45210987E', 'email' => 'fernando.garcia@gmail.com', 'tel' => '632990011', 'dom' => 'Calle Castillo 14, Santa Cruz de Tenerife', 'cp' => '38003'],
            ['nombre' => 'Rosa María', 'apellidos' => 'Hernández González', 'empresa_id' => 2, 'tipo' => $particular, 'dni' => '78098765F', 'email' => 'rosamaria.hernandez@outlook.com', 'tel' => '633112244', 'dom' => 'Calle Heraclio Sánchez 40, San Cristóbal de La Laguna', 'cp' => '38201'],
            ['nombre' => 'Alejandro', 'apellidos' => 'Díaz Afonso', 'empresa_id' => 2, 'tipo' => $particular, 'dni' => '46987654G', 'email' => 'alejandro.diaz@gmail.com', 'tel' => '634334466', 'dom' => 'Avenida Chayofita 3, Los Cristianos, Arona', 'cp' => '38650'],
            ['nombre' => 'Hoteles Tinerfe', 'apellidos' => 'S.L.', 'empresa_id' => 2, 'tipo' => $empresa, 'dni' => 'B38445566', 'email' => 'compras@hotelestinerfe.com', 'tel' => '922445566', 'dom' => 'Avenida Tres de Mayo 7, Santa Cruz de Tenerife', 'cp' => '38005'],
            ['nombre' => 'Logística Teide', 'apellidos' => 'S.L.', 'empresa_id' => 2, 'tipo' => $flota, 'dni' => 'B38778899', 'email' => 'flotas@logistica-teide.com', 'tel' => '922778800', 'dom' => 'Pol. Ind. Güímar, Nave 12', 'cp' => '38500'],
            ['nombre' => 'Cabildo', 'apellidos' => 'de Tenerife', 'empresa_id' => 2, 'tipo' => $admin, 'dni' => 'P3800000D', 'email' => 'contratacion@tenerife.es', 'tel' => '922239500', 'dom' => 'Plaza de España 1', 'cp' => '38003'],
            ['nombre' => 'Nuria', 'apellidos' => 'Melián Rivero', 'empresa_id' => 2, 'tipo' => $autonomo, 'dni' => '48334455P', 'email' => 'nuria.melian@gmail.com', 'tel' => '641445566', 'dom' => 'Calle Viera y Clavijo 27, La Laguna', 'cp' => '38204'],
            ['nombre' => 'Raúl', 'apellidos' => 'Pérez Delgado', 'empresa_id' => 2, 'tipo' => $particular, 'dni' => '49445566Q', 'email' => 'raul.perez@outlook.com', 'tel' => '642556677', 'dom' => 'Calle Núñez de la Peña 3, Santa Cruz', 'cp' => '38002'],
            ['nombre' => 'Cristina', 'apellidos' => 'Domínguez Alonso', 'empresa_id' => 2, 'tipo' => $vip, 'dni' => '50556677R', 'email' => 'cristina.dominguez@gmail.com', 'tel' => '643667788', 'dom' => 'Avenida Anaga 12, Santa Cruz', 'cp' => '38001'],

            // Lanzarote (empresa 3)
            ['nombre' => 'Dolores', 'apellidos' => 'Betancort Curbelo', 'empresa_id' => 3, 'tipo' => $particular, 'dni' => '43876543H', 'email' => 'dolores.betancort@gmail.com', 'tel' => '635556688', 'dom' => 'Calle Real 78, Arrecife', 'cp' => '35500'],
            ['nombre' => 'Juan Carlos', 'apellidos' => 'Morales Páez', 'empresa_id' => 3, 'tipo' => $particular, 'dni' => '42765432I', 'email' => 'juancarlos.morales@hotmail.com', 'tel' => '636778800', 'dom' => 'Calle Noruega 5, Puerto del Carmen, Tías', 'cp' => '35510'],
            ['nombre' => 'Turismos Lanzarote', 'apellidos' => 'S.L.', 'empresa_id' => 3, 'tipo' => $flota, 'dni' => 'B35820000', 'email' => 'operaciones@turismos-lanzarote.com', 'tel' => '928820000', 'dom' => 'Avenida del Mar 15, Arrecife', 'cp' => '35500'],
            ['nombre' => 'Isabel', 'apellidos' => 'Perdomo Fajardo', 'empresa_id' => 3, 'tipo' => $particular, 'dni' => '51667788S', 'email' => 'isabel.perdomo@gmail.com', 'tel' => '644778899', 'dom' => 'Calle Teide 8, Costa Teguise', 'cp' => '35508'],
            ['nombre' => 'Óscar', 'apellidos' => 'Curbelo Martín', 'empresa_id' => 3, 'tipo' => $autonomo, 'dni' => '52778899T', 'email' => 'oscar.curbelo@hotmail.com', 'tel' => '645889900', 'dom' => 'Calle Los Geranios 12, Playa Blanca', 'cp' => '35580'],
        ];

        foreach ($clientes as $c) {
            Cliente::firstOrCreate(
                ['dni' => $c['dni']],
                [
                    'nombre' => $c['nombre'],
                    'apellidos' => $c['apellidos'],
                    'empresa_id' => $c['empresa_id'],
                    'tipo_cliente_id' => $c['tipo'],
                    'dni' => $c['dni'],
                    'email' => $c['email'],
                    'telefono' => $c['tel'],
                    'domicilio' => $c['dom'],
                    'codigo_postal' => $c['cp'],
                ]
            );
        }
    }
}
