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

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartamentoRepositoryInterface::class, DepartamentoRepository::class);
        $this->app->bind(CentroRepositoryInterface::class, CentroRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, ClienteRepository::class);
        $this->app->bind(VehiculoRepositoryInterface::class, VehiculoRepository::class);
    }

    public function boot(): void
    {
        //
    }
}