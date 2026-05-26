<?php

namespace Database\Seeders;

use App\Helpers\UserRestrictionHelper;
use App\Models\Centro;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin — único. Meng Fei Dai, Informática.
        $superAdmin = User::create([
            'nombre' => 'Meng Fei',
            'apellidos' => 'Dai',
            'empresa_id' => 1,
            'departamento_id' => 7, // Informática
            'centro_id' => 1,
            'email' => 'mengfei.dai@grupo-dai.com',
            'telefono' => '928301501',
            'extension' => '100',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Administrador — Administración Gran Canaria
        User::create([
            'nombre' => 'Carmen',
            'apellidos' => 'Santana Medina',
            'empresa_id' => 1,
            'departamento_id' => 1,
            'centro_id' => 1,
            'email' => 'carmen.santana@grupo-dai.com',
            'telefono' => '928301502',
            'extension' => '101',
            'password' => Hash::make('password'),
        ])->assignRole('Administrador');

        // Gerente — Dirección Tenerife
        User::create([
            'nombre' => 'Francisco',
            'apellidos' => 'Hernández Pérez',
            'empresa_id' => 2,
            'departamento_id' => 8,
            'centro_id' => 4,
            'email' => 'francisco.hernandez@grupo-dai.com',
            'telefono' => '922653201',
            'extension' => '200',
            'password' => Hash::make('password'),
        ])->assignRole('Gerente');

        // Vendedores (sin restricciones, operan en su centro pero ven datos globales)
        User::create([
            'nombre' => 'María del Carmen',
            'apellidos' => 'González Suárez',
            'empresa_id' => 1,
            'departamento_id' => 2,
            'centro_id' => 2,
            'email' => 'maria.gonzalez@grupo-dai.com',
            'telefono' => '628445566',
            'extension' => '301',
            'password' => Hash::make('password'),
        ])->assignRole('Vendedor');

        User::create([
            'nombre' => 'José Antonio',
            'apellidos' => 'Rodríguez Dorta',
            'empresa_id' => 2,
            'departamento_id' => 2,
            'centro_id' => 5,
            'email' => 'joseantonio.rodriguez@grupo-dai.com',
            'telefono' => '622778899',
            'extension' => '302',
            'password' => Hash::make('password'),
        ])->assignRole('Vendedor');

        // Consultor
        User::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Cabrera Betancort',
            'empresa_id' => 3,
            'departamento_id' => 1,
            'centro_id' => 7,
            'email' => 'pedro.cabrera@grupo-dai.com',
            'telefono' => '928812301',
            'extension' => '400',
            'password' => Hash::make('password'),
        ])->assignRole('Consultor');

        // ──────────────────────────────────────────────────────
        // Usuarios con restricciones (morph polimórficas)
        // ──────────────────────────────────────────────────────

        // 1) Vendedor restringido a empresa Tenerife (solo ve datos de empresa 2)
        $vendedorTF = User::create([
            'nombre' => 'Laura',
            'apellidos' => 'Martín Afonso',
            'empresa_id' => 2,
            'departamento_id' => 2,
            'centro_id' => 4,
            'email' => 'laura.martin@grupo-dai.com',
            'telefono' => '922653210',
            'extension' => '303',
            'password' => Hash::make('password'),
        ]);
        $vendedorTF->assignRole('Vendedor');
        UserRestrictionHelper::addRestriction(
            $vendedorTF,
            UserRestrictionHelper::TYPE_EMPRESA,
            Empresa::find(2)
        );

        // 2) Gerente restringido a dos centros específicos de Gran Canaria
        $gerenteGC = User::create([
            'nombre' => 'Antonio',
            'apellidos' => 'Ramírez Déniz',
            'empresa_id' => 1,
            'departamento_id' => 8,
            'centro_id' => 1,
            'email' => 'antonio.ramirez@grupo-dai.com',
            'telefono' => '928301520',
            'extension' => '201',
            'password' => Hash::make('password'),
        ]);
        $gerenteGC->assignRole('Gerente');
        foreach (Centro::whereIn('id', [1, 2])->get() as $centro) {
            UserRestrictionHelper::addRestriction(
                $gerenteGC,
                UserRestrictionHelper::TYPE_CENTRO,
                $centro
            );
        }
    }
}
