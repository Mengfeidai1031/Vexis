<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\DepartamentoRepositoryInterface;
use App\Repositories\DepartamentoRepository;
use App\Repositories\Interfaces\CentroRepositoryInterface;
use App\Repositories\CentroRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use App\Repositories\ClienteRepository;
use App\Repositories\Interfaces\VehiculoRepositoryInterface;
use App\Repositories\VehiculoRepository;
use App\Repositories\Interfaces\OfertaRepositoryInterface;
use App\Repositories\OfertaRepository;
use App\Repositories\Interfaces\RestriccionRepositoryInterface;
use App\Repositories\RestriccionRepository;
use Illuminate\Support\Facades\Gate;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OfertaCabecera;
use App\Models\UserRestriction;
use App\Models\Centro;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Empresa;
use App\Policies\ClientePolicy;
use App\Policies\VehiculoPolicy;
use App\Policies\OfertaPolicy;
use App\Policies\UserRestrictionPolicy;
use App\Policies\CentroPolicy;
use App\Policies\DepartamentoPolicy;
use App\Policies\UserPolicy;
use App\Policies\EmpresaPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar el repositorio de usuarios
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartamentoRepositoryInterface::class, DepartamentoRepository::class);
        $this->app->bind(CentroRepositoryInterface::class, CentroRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, ClienteRepository::class);
        $this->app->bind(VehiculoRepositoryInterface::class, VehiculoRepository::class);
        $this->app->bind(OfertaRepositoryInterface::class, OfertaRepository::class);
        $this->app->bind(RestriccionRepositoryInterface::class, RestriccionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar políticas de autorización
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Vehiculo::class, VehiculoPolicy::class);
        Gate::policy(OfertaCabecera::class, OfertaPolicy::class);
        Gate::policy(UserRestriction::class, UserRestrictionPolicy::class);
        Gate::policy(Centro::class, CentroPolicy::class);
        Gate::policy(Departamento::class, DepartamentoPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Empresa::class, EmpresaPolicy::class);
    }
}