<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentoController;

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

    // Rutas de usuarios (CRUD completo)
    Route::resource('users', UserController::class);
    Route::get('/api/centros-by-empresa', [UserController::class, 'getCentrosByEmpresa'])
        ->name('api.centros-by-empresa');

    // CRUD de departamentos
    Route::resource('departamentos', DepartamentoController::class);
});