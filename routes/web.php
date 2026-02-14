<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\RoleController;

// Ruta pública (página de inicio)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación (públicas)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD de usuarios - Solo con permisos
    Route::middleware(['permission:ver usuarios'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    
    Route::middleware(['permission:crear usuarios'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    
    Route::middleware(['permission:editar usuarios'])->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}', [UserController::class, 'update']);
    });
    
    Route::middleware(['permission:eliminar usuarios'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/api/centros-by-empresa', [UserController::class, 'getCentrosByEmpresa'])
        ->name('api.centros-by-empresa');

    // CRUD de departamentos - Solo con permisos
    Route::middleware(['permission:ver departamentos'])->group(function () {
        Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
        Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('departamentos.show');
    });
    
    Route::middleware(['permission:crear departamentos'])->group(function () {
        Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
        Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    });
    
    Route::middleware(['permission:editar departamentos'])->group(function () {
        Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit');
        Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');
        Route::patch('/departamentos/{departamento}', [DepartamentoController::class, 'update']);
    });
    
    Route::middleware(['permission:eliminar departamentos'])->group(function () {
        Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
    });

    // CRUD de centros - Solo con permisos
    Route::middleware(['permission:ver centros'])->group(function () {
        Route::get('/centros', [CentroController::class, 'index'])->name('centros.index');
        Route::get('/centros/{centro}', [CentroController::class, 'show'])->name('centros.show');
    });
    
    Route::middleware(['permission:crear centros'])->group(function () {
        Route::get('/centros/create', [CentroController::class, 'create'])->name('centros.create');
        Route::post('/centros', [CentroController::class, 'store'])->name('centros.store');
    });
    
    Route::middleware(['permission:editar centros'])->group(function () {
        Route::get('/centros/{centro}/edit', [CentroController::class, 'edit'])->name('centros.edit');
        Route::put('/centros/{centro}', [CentroController::class, 'update'])->name('centros.update');
        Route::patch('/centros/{centro}', [CentroController::class, 'update']);
    });
    
    Route::middleware(['permission:eliminar centros'])->group(function () {
        Route::delete('/centros/{centro}', [CentroController::class, 'destroy'])->name('centros.destroy');
    });

    // CRUD de roles - Solo con permisos
    Route::middleware(['permission:ver roles'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    });
    
    Route::middleware(['permission:crear roles'])->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });
    
    Route::middleware(['permission:editar roles'])->group(function () {
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::patch('/roles/{role}', [RoleController::class, 'update']);
    });
    
    Route::middleware(['permission:eliminar roles'])->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
});