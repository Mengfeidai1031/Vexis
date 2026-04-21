<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'module' => \App\Http\Middleware\CheckModuleEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (\Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('security')->error('exception.'.class_basename($e), [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl() ?? null,
                'ip' => request()->ip() ?? null,
                'user_id' => auth()->id(),
                'file' => $e->getFile().':'.$e->getLine(),
            ]);
        });
    })->create();
