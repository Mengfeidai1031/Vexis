<?php

namespace App\Console\Commands;

use App\Helpers\UserRestrictionHelper;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Repositories\ClienteRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestUserRestrictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:restrictions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar restricciones de usuario. Si no se especifica email, muestra opciones.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $this->showMenu();
            return 0;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuario no encontrado: {$email}");
            return 1;
        }

        $this->testUserRestrictions($user);
        return 0;
    }

    protected function showMenu()
    {
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('Prueba de Restricciones de Usuario');
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('');
        $this->info('Uso: php artisan test:restrictions {email}');
        $this->info('');
        $this->info('Usuarios de prueba disponibles:');
        $this->info('  - sin-restricciones@test.com');
        $this->info('  - empresa-1@test.com');
        $this->info('  - multi-empresa@test.com');
        $this->info('  - clientes-especificos@test.com');
        $this->info('');
        $this->info('O crea usuarios con el seeder:');
        $this->info('  php artisan db:seed --class=UserRestrictionsTestSeeder');
        $this->info('═══════════════════════════════════════════════════════');
    }

    protected function testUserRestrictions(User $user)
    {
        $this->info("Probando restricciones para: {$user->nombre} {$user->apellidos} ({$user->email})");
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('');

        // Mostrar restricciones
        $this->info('📋 Restricciones del usuario:');
        if (UserRestrictionHelper::hasRestrictions($user)) {
            $restrictions = UserRestrictionHelper::getRestrictions($user);
            foreach ($restrictions as $r) {
                $this->line("  - {$r->restriction_type}: {$r->restriction_value}");
            }
        } else {
            $this->line('  ✅ Sin restricciones (puede ver todo)');
        }
        $this->info('');

        // Probar acceso a empresas
        $this->info('🏢 Empresas permitidas:');
        $empresaIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_EMPRESA);
        if (empty($empresaIds)) {
            $this->line('  ✅ Todas las empresas');
        } else {
            $empresas = Empresa::whereIn('id', $empresaIds)->get();
            foreach ($empresas as $empresa) {
                $this->line("  - {$empresa->nombre} (ID: {$empresa->id})");
            }
        }
        $this->info('');

        // Probar acceso a clientes
        $this->info('👥 Clientes permitidos:');
        $clienteIds = UserRestrictionHelper::getRestrictionValues($user, UserRestrictionHelper::TYPE_CLIENTE);
        if (empty($clienteIds)) {
            $this->line('  ✅ Todos los clientes (según restricciones de empresa)');
        } else {
            $clientes = Cliente::whereIn('id', $clienteIds)->get();
            foreach ($clientes as $cliente) {
                $this->line("  - {$cliente->nombre_completo} (ID: {$cliente->id})");
            }
        }
        $this->info('');

        // Probar repositorio
        $this->info('🔍 Probando repositorio de clientes:');
        Auth::login($user);
        
        try {
            $repo = new ClienteRepository();
            $clientes = $repo->all();
            
            $this->line("  Total de clientes visibles: {$clientes->total()}");
            
            if ($clientes->total() > 0) {
                $this->line('  Primeros 5 clientes:');
                foreach ($clientes->take(5) as $cliente) {
                    $this->line("    - {$cliente->nombre_completo} (Empresa: {$cliente->empresa_id})");
                }
            }
        } catch (\Exception $e) {
            $this->error("  Error: {$e->getMessage()}");
        }
        
        Auth::logout();
        $this->info('');

        $this->info('✅ Prueba completada');
    }
}
