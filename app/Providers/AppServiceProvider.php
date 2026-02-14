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

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartamentoRepositoryInterface::class, DepartamentoRepository::class);
        $this->app->bind(CentroRepositoryInterface::class, CentroRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    public function boot(): void
    {
        //
    }
}