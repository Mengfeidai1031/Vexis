<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        // Clientes Gran Canaria (empresa_id: 1)
        Cliente::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Rodríguez Vega',
            'empresa_id' => 1,
            'dni' => '42876543A',
            'email' => 'carlos.rodriguez@gmail.com',
            'telefono' => '628112233',
            'domicilio' => 'Calle Triana 52, Las Palmas de Gran Canaria',
            'codigo_postal' => '35002',
        ]);

        Cliente::create([
            'nombre' => 'Ana Belén',
            'apellidos' => 'Martín Suárez',
            'empresa_id' => 1,
            'dni' => '43765432B',
            'email' => 'anabelen.martin@hotmail.com',
            'telefono' => '629334455',
            'domicilio' => 'Calle Secretario Artiles 24, Telde',
            'codigo_postal' => '35200',
        ]);

        Cliente::create([
            'nombre' => 'Miguel Ángel',
            'apellidos' => 'Sánchez Ojeda',
            'empresa_id' => 1,
            'dni' => '78654321C',
            'email' => 'miguelangel.sanchez@yahoo.es',
            'telefono' => '630556677',
            'domicilio' => 'Avenida de Canarias 128, Vecindario',
            'codigo_postal' => '35110',
        ]);

        Cliente::create([
            'nombre' => 'Luisa',
            'apellidos' => 'Peñate Cabrera',
            'empresa_id' => 1,
            'dni' => '44321098D',
            'email' => 'luisa.penate@gmail.com',
            'telefono' => '631778899',
            'domicilio' => 'Calle León y Castillo 200, Las Palmas de Gran Canaria',
            'codigo_postal' => '35004',
        ]);

        // Clientes Tenerife (empresa_id: 2)
        Cliente::create([
            'nombre' => 'Fernando',
            'apellidos' => 'García Dorta',
            'empresa_id' => 2,
            'dni' => '45210987E',
            'email' => 'fernando.garcia@gmail.com',
            'telefono' => '632990011',
            'domicilio' => 'Calle Castillo 14, Santa Cruz de Tenerife',
            'codigo_postal' => '38003',
        ]);

        Cliente::create([
            'nombre' => 'Rosa María',
            'apellidos' => 'Hernández González',
            'empresa_id' => 2,
            'dni' => '78098765F',
            'email' => 'rosamaria.hernandez@outlook.com',
            'telefono' => '633112244',
            'domicilio' => 'Calle Heraclio Sánchez 40, San Cristóbal de La Laguna',
            'codigo_postal' => '38201',
        ]);

        Cliente::create([
            'nombre' => 'Alejandro',
            'apellidos' => 'Díaz Afonso',
            'empresa_id' => 2,
            'dni' => '46987654G',
            'email' => 'alejandro.diaz@gmail.com',
            'telefono' => '634334466',
            'domicilio' => 'Avenida Chayofita 3, Los Cristianos, Arona',
            'codigo_postal' => '38650',
        ]);

        // Clientes Lanzarote (empresa_id: 3)
        Cliente::create([
            'nombre' => 'Dolores',
            'apellidos' => 'Betancort Curbelo',
            'empresa_id' => 3,
            'dni' => '43876543H',
            'email' => 'dolores.betancort@gmail.com',
            'telefono' => '635556688',
            'domicilio' => 'Calle Real 78, Arrecife',
            'codigo_postal' => '35500',
        ]);

        Cliente::create([
            'nombre' => 'Juan Carlos',
            'apellidos' => 'Morales Páez',
            'empresa_id' => 3,
            'dni' => '42765432I',
            'email' => 'juancarlos.morales@hotmail.com',
            'telefono' => '636778800',
            'domicilio' => 'Calle Noruega 5, Puerto del Carmen, Tías',
            'codigo_postal' => '35510',
        ]);

        Cliente::create([
            'nombre' => 'Beatriz',
            'apellidos' => 'Cabrera Robayna',
            'empresa_id' => 1,
            'dni' => '44654321J',
            'email' => 'beatriz.cabrera@gmail.com',
            'telefono' => '637990022',
            'domicilio' => 'Calle Agustín Millares 15, Arucas',
            'codigo_postal' => '35400',
        ]);
    }
}
