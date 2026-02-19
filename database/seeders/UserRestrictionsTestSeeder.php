<?php

namespace Database\Seeders;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRestrictionsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando usuarios de prueba para restricciones...');

        // Verificar que existen empresas
        $empresas = Empresa::all();
        if ($empresas->isEmpty()) {
            $this->command->error('No hay empresas en la base de datos. Crea al menos 2 empresas primero.');
            return;
        }

        $empresa1 = $empresas->first();
        $empresa2 = $empresas->skip(1)->first() ?? $empresa1;

        // Verificar que existe al menos un departamento y centro
        $departamento = \App\Models\Departamento::first();
        $centro = \App\Models\Centro::first();

        if (!$departamento || !$centro) {
            $this->command->error('Necesitas crear al menos un departamento y un centro.');
            return;
        }

        // 1. Usuario SIN restricciones (ve todo)
        $user1 = User::firstOrCreate(
            ['email' => 'sin-restricciones@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Sin Restricciones',
                'password' => Hash::make('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );
        $this->command->info("Usuario 1 creado: {$user1->email} (sin restricciones)");

        // 2. Usuario restringido a UNA empresa (usando morph - modelo directamente)
        $user2 = User::firstOrCreate(
            ['email' => 'empresa-1@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Empresa 1',
                'password' => Hash::make('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );
        // Con morph: puedes pasar el modelo directamente
        UserRestrictionHelper::addRestriction(
            $user2,
            UserRestrictionHelper::TYPE_EMPRESA,
            $empresa1 // Pasamos el modelo, no solo el ID
        );
        $this->command->info("Usuario 2 creado: {$user2->email} (solo empresa {$empresa1->nombre})");

        // 3. Usuario restringido a MÚLTIPLES empresas (usando morph)
        if ($empresa2) {
            $user3 = User::firstOrCreate(
                ['email' => 'multi-empresa@test.com'],
                [
                    'nombre' => 'Usuario',
                    'apellidos' => 'Multi Empresa',
                    'password' => Hash::make('password'),
                    'empresa_id' => $empresa1->id,
                    'departamento_id' => $departamento->id,
                    'centro_id' => $centro->id,
                ]
            );
            // Con morph: añadir cada empresa como modelo
            UserRestrictionHelper::addRestriction($user3, UserRestrictionHelper::TYPE_EMPRESA, $empresa1);
            UserRestrictionHelper::addRestriction($user3, UserRestrictionHelper::TYPE_EMPRESA, $empresa2);
            // O seguir usando IDs si prefieres:
            // UserRestrictionHelper::addRestrictions($user3, UserRestrictionHelper::TYPE_EMPRESA, [$empresa1->id, $empresa2->id]);
            $this->command->info("Usuario 3 creado: {$user3->email} (empresas {$empresa1->nombre} y {$empresa2->nombre})");
        }

        // 4. Usuario restringido a CLIENTES específicos (usando morph)
        $clientes = Cliente::take(3)->get();
        if ($clientes->isNotEmpty()) {
            $user4 = User::firstOrCreate(
                ['email' => 'clientes-especificos@test.com'],
                [
                    'nombre' => 'Usuario',
                    'apellidos' => 'Clientes Específicos',
                    'password' => Hash::make('password'),
                    'empresa_id' => $empresa1->id,
                    'departamento_id' => $departamento->id,
                    'centro_id' => $centro->id,
                ]
            );
            // Con morph: añadir cada cliente como modelo
            foreach ($clientes as $cliente) {
                UserRestrictionHelper::addRestriction($user4, UserRestrictionHelper::TYPE_CLIENTE, $cliente);
            }
            $clienteNombres = $clientes->pluck('nombre_completo')->toArray();
            $this->command->info("Usuario 4 creado: {$user4->email} (solo clientes: " . implode(', ', $clienteNombres) . ")");
        }

        // 5. Usuario con restricción de VEHÍCULO (ejemplo adicional con morph)
        $vehiculos = \App\Models\Vehiculo::take(2)->get();
        if ($vehiculos->isNotEmpty()) {
            $user5 = User::firstOrCreate(
                ['email' => 'vehiculos-especificos@test.com'],
                [
                    'nombre' => 'Usuario',
                    'apellidos' => 'Vehículos Específicos',
                    'password' => Hash::make('password'),
                    'empresa_id' => $empresa1->id,
                    'departamento_id' => $departamento->id,
                    'centro_id' => $centro->id,
                ]
            );
            // Con morph: añadir vehículos como modelos
            foreach ($vehiculos as $vehiculo) {
                UserRestrictionHelper::addRestriction($user5, UserRestrictionHelper::TYPE_VEHICULO, $vehiculo);
            }
            $vehiculoModelos = $vehiculos->pluck('modelo')->toArray();
            $this->command->info("Usuario 5 creado: {$user5->email} (solo vehículos: " . implode(', ', $vehiculoModelos) . ")");
        }

        // 6. Usuario con restricción de CENTRO (ejemplo adicional con morph)
        $centros = \App\Models\Centro::take(2)->get();
        if ($centros->count() > 1) {
            $user6 = User::firstOrCreate(
                ['email' => 'centros-especificos@test.com'],
                [
                    'nombre' => 'Usuario',
                    'apellidos' => 'Centros Específicos',
                    'password' => Hash::make('password'),
                    'empresa_id' => $empresa1->id,
                    'departamento_id' => $departamento->id,
                    'centro_id' => $centro->id,
                ]
            );
            // Con morph: añadir centros como modelos
            foreach ($centros as $centroModel) {
                UserRestrictionHelper::addRestriction($user6, UserRestrictionHelper::TYPE_CENTRO, $centroModel);
            }
            $centroNombres = $centros->pluck('nombre')->toArray();
            $this->command->info("Usuario 6 creado: {$user6->email} (solo centros: " . implode(', ', $centroNombres) . ")");
        }

        // 7. Usuario con MÚLTIPLES tipos de restricciones (ejemplo avanzado con morph)
        $user7 = User::firstOrCreate(
            ['email' => 'multi-restricciones@test.com'],
            [
                'nombre' => 'Usuario',
                'apellidos' => 'Multi Restricciones',
                'password' => Hash::make('password'),
                'empresa_id' => $empresa1->id,
                'departamento_id' => $departamento->id,
                'centro_id' => $centro->id,
            ]
        );
        // Restricción de empresa
        UserRestrictionHelper::addRestriction($user7, UserRestrictionHelper::TYPE_EMPRESA, $empresa1);
        // Restricción de cliente (si hay)
        if ($clientes->isNotEmpty()) {
            UserRestrictionHelper::addRestriction($user7, UserRestrictionHelper::TYPE_CLIENTE, $clientes->first());
        }
        $this->command->info("Usuario 7 creado: {$user7->email} (múltiples tipos de restricciones)");

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('Usuarios de prueba creados (usando sistema MORPH):');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('1. sin-restricciones@test.com | Password: password | Ve: TODO');
        $this->command->info('2. empresa-1@test.com | Password: password | Ve: Solo empresa ' . $empresa1->nombre);
        if ($empresa2) {
            $this->command->info('3. multi-empresa@test.com | Password: password | Ve: Empresas ' . $empresa1->nombre . ' y ' . $empresa2->nombre);
        }
        if ($clientes->isNotEmpty()) {
            $this->command->info('4. clientes-especificos@test.com | Password: password | Ve: Solo clientes específicos');
        }
        if ($vehiculos->isNotEmpty()) {
            $this->command->info('5. vehiculos-especificos@test.com | Password: password | Ve: Solo vehículos específicos');
        }
        if (isset($centros) && $centros->count() > 1) {
            $this->command->info('6. centros-especificos@test.com | Password: password | Ve: Solo centros específicos');
        }
        $this->command->info('7. multi-restricciones@test.com | Password: password | Ve: Múltiples tipos de restricciones');
        $this->command->info('');
        $this->command->info('💡 Nota: Estos usuarios usan el sistema MORPH (relaciones polimórficas)');
        $this->command->info('   Puedes acceder a los modelos directamente: $restriction->restrictable');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
