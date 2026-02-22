<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RestriccionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\CampaniaController;
use App\Http\Controllers\NamingPcController;

// Ruta pública (página de inicio)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación (públicas)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Módulos - Inicio
    Route::get('/gestion', function () { return view('gestion.inicio'); })->name('gestion.inicio');
    Route::get('/comercial', function () { return view('comercial.inicio'); })->name('comercial.inicio');

    // Gestión - Seguridad
    Route::get('/gestion/permisos', function () {
        $roles = \Spatie\Permission\Models\Role::orderBy('id')->get();
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();
        return view('gestion.permisos', compact('roles', 'permissions'));
    })->name('gestion.permisos')->middleware('permission:ver roles');

    Route::get('/gestion/politica', function () {
        return view('gestion.politica');
    })->name('gestion.politica');

    // Gestión - Mantenimiento: Marcas
    Route::get('/gestion/marcas', function () {
        $marcas = \App\Models\Marca::orderBy('nombre')->get();
        return view('gestion.marcas', compact('marcas'));
    })->name('gestion.marcas');

    // CRUD de Empresas
    Route::middleware(['permission:crear empresas'])->group(function () {
        Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
        Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    });
    Route::middleware(['permission:ver empresas'])->group(function () {
        Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
        Route::get('/empresas/{empresa}', [EmpresaController::class, 'show'])->name('empresas.show');
    });
    Route::middleware(['permission:editar empresas'])->group(function () {
        Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
        Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
    });
    Route::middleware(['permission:eliminar empresas'])->group(function () {
        Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');
    });

    // CRUD de Noticias
    Route::middleware(['permission:crear noticias'])->group(function () {
        Route::get('/noticias/create', [NoticiaController::class, 'create'])->name('noticias.create');
        Route::post('/noticias', [NoticiaController::class, 'store'])->name('noticias.store');
    });
    Route::middleware(['permission:ver noticias'])->group(function () {
        Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
        Route::get('/noticias/{noticia}', [NoticiaController::class, 'show'])->name('noticias.show');
    });
    Route::middleware(['permission:editar noticias'])->group(function () {
        Route::get('/noticias/{noticia}/edit', [NoticiaController::class, 'edit'])->name('noticias.edit');
        Route::put('/noticias/{noticia}', [NoticiaController::class, 'update'])->name('noticias.update');
    });
    Route::middleware(['permission:eliminar noticias'])->group(function () {
        Route::delete('/noticias/{noticia}', [NoticiaController::class, 'destroy'])->name('noticias.destroy');
    });

    // CRUD de Campañas
    Route::middleware(['permission:crear campanias'])->group(function () {
        Route::get('/campanias/create', [CampaniaController::class, 'create'])->name('campanias.create');
        Route::post('/campanias', [CampaniaController::class, 'store'])->name('campanias.store');
    });
    Route::middleware(['permission:ver campanias'])->group(function () {
        Route::get('/campanias', [CampaniaController::class, 'index'])->name('campanias.index');
        Route::get('/campanias/{campania}', [CampaniaController::class, 'show'])->name('campanias.show');
    });
    Route::middleware(['permission:editar campanias'])->group(function () {
        Route::get('/campanias/{campania}/edit', [CampaniaController::class, 'edit'])->name('campanias.edit');
        Route::put('/campanias/{campania}', [CampaniaController::class, 'update'])->name('campanias.update');
    });
    Route::middleware(['permission:eliminar campanias'])->group(function () {
        Route::delete('/campanias/{campania}', [CampaniaController::class, 'destroy'])->name('campanias.destroy');
        Route::delete('/campanias/fotos/{foto}', [CampaniaController::class, 'destroyFoto'])->name('campanias.fotos.destroy');
    });

    // CRUD de Naming PCs
    Route::middleware(['permission:crear naming-pcs'])->group(function () {
        Route::get('/naming-pcs/create', [NamingPcController::class, 'create'])->name('naming-pcs.create');
        Route::post('/naming-pcs', [NamingPcController::class, 'store'])->name('naming-pcs.store');
    });
    Route::middleware(['permission:ver naming-pcs'])->group(function () {
        Route::get('/naming-pcs', [NamingPcController::class, 'index'])->name('naming-pcs.index');
        Route::get('/naming-pcs/{namingPc}', [NamingPcController::class, 'show'])->name('naming-pcs.show');
    });
    Route::middleware(['permission:editar naming-pcs'])->group(function () {
        Route::get('/naming-pcs/{namingPc}/edit', [NamingPcController::class, 'edit'])->name('naming-pcs.edit');
        Route::put('/naming-pcs/{namingPc}', [NamingPcController::class, 'update'])->name('naming-pcs.update');
    });
    Route::middleware(['permission:eliminar naming-pcs'])->group(function () {
        Route::delete('/naming-pcs/{namingPc}', [NamingPcController::class, 'destroy'])->name('naming-pcs.destroy');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // CRUD de usuarios - Solo con permisos
    // IMPORTANTE: Las rutas específicas (/create) deben ir ANTES de las dinámicas (/{user})
    Route::middleware(['permission:crear usuarios'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware(['permission:ver usuarios'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->middleware('can:view,user')
            ->name('users.show');
    });
    
    Route::middleware(['permission:editar usuarios'])->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('can:update,user')
            ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->middleware('can:update,user')
            ->name('users.update');
        Route::patch('/users/{user}', [UserController::class, 'update'])
            ->middleware('can:update,user');
    });
    
    Route::middleware(['permission:eliminar usuarios'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('can:delete,user')
            ->name('users.destroy');
    });

    Route::get('/api/centros-by-empresa', [UserController::class, 'getCentrosByEmpresa'])
        ->name('api.centros-by-empresa');

    // CRUD de departamentos - Solo con permisos
    // IMPORTANTE: Las rutas específicas (/create) deben ir ANTES de las dinámicas (/{departamento})
    Route::middleware(['permission:crear departamentos'])->group(function () {
        Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
        Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    });

    Route::middleware(['permission:ver departamentos'])->group(function () {
        Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index');
        Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])
            ->middleware('can:view,departamento')
            ->name('departamentos.show');
    });
    
    Route::middleware(['permission:editar departamentos'])->group(function () {
        Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])
            ->middleware('can:update,departamento')
            ->name('departamentos.edit');
        Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])
            ->middleware('can:update,departamento')
            ->name('departamentos.update');
        Route::patch('/departamentos/{departamento}', [DepartamentoController::class, 'update'])
            ->middleware('can:update,departamento');
    });
    
    Route::middleware(['permission:eliminar departamentos'])->group(function () {
        Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])
            ->middleware('can:delete,departamento')
            ->name('departamentos.destroy');
    });

    // CRUD de centros - Solo con permisos
    // IMPORTANTE: Las rutas específicas (/create) deben ir ANTES de las dinámicas (/{centro})
    Route::middleware(['permission:crear centros'])->group(function () {
        Route::get('/centros/create', [CentroController::class, 'create'])->name('centros.create');
        Route::post('/centros', [CentroController::class, 'store'])->name('centros.store');
    });

    Route::middleware(['permission:ver centros'])->group(function () {
        Route::get('/centros', [CentroController::class, 'index'])->name('centros.index');
        Route::get('/centros/{centro}', [CentroController::class, 'show'])
            ->middleware('can:view,centro')
            ->name('centros.show');
    });
    
    Route::middleware(['permission:editar centros'])->group(function () {
        Route::get('/centros/{centro}/edit', [CentroController::class, 'edit'])
            ->middleware('can:update,centro')
            ->name('centros.edit');
        Route::put('/centros/{centro}', [CentroController::class, 'update'])
            ->middleware('can:update,centro')
            ->name('centros.update');
        Route::patch('/centros/{centro}', [CentroController::class, 'update'])
            ->middleware('can:update,centro');
    });
    
    Route::middleware(['permission:eliminar centros'])->group(function () {
        Route::delete('/centros/{centro}', [CentroController::class, 'destroy'])
            ->middleware('can:delete,centro')
            ->name('centros.destroy');
    });

    // CRUD de roles - Solo con permisos
    // IMPORTANTE: Las rutas específicas (/create) deben ir ANTES de las dinámicas (/{role})
    Route::middleware(['permission:crear roles'])->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });

    Route::middleware(['permission:ver roles'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    });
    
    Route::middleware(['permission:editar roles'])->group(function () {
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::patch('/roles/{role}', [RoleController::class, 'update']);
    });
    
    Route::middleware(['permission:eliminar roles'])->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // CRUD de restricciones - Solo con permisos
    Route::middleware(['permission:crear restricciones'])->group(function () {
        Route::get('/restricciones/create', [RestriccionController::class, 'create'])->name('restricciones.create');
        Route::post('/restricciones', [RestriccionController::class, 'store'])->name('restricciones.store');
    });

    Route::middleware(['permission:ver restricciones'])->group(function () {
        Route::get('/restricciones', [RestriccionController::class, 'index'])->name('restricciones.index');
        Route::get('/restricciones/{restriccion}', [RestriccionController::class, 'show'])
            ->middleware('can:view,restriccion')
            ->name('restricciones.show');
    });
    
    Route::middleware(['permission:editar restricciones'])->group(function () {
        Route::get('/restricciones/{restriccion}/edit', [RestriccionController::class, 'edit'])
            ->middleware('can:update,restriccion')
            ->name('restricciones.edit');
        Route::put('/restricciones/{restriccion}', [RestriccionController::class, 'update'])
            ->middleware('can:update,restriccion')
            ->name('restricciones.update');
        Route::patch('/restricciones/{restriccion}', [RestriccionController::class, 'update'])
            ->middleware('can:update,restriccion');
    });
    
    Route::middleware(['permission:eliminar restricciones'])->group(function () {
        Route::delete('/restricciones/{restriccion}', [RestriccionController::class, 'destroy'])
            ->middleware('can:delete,restriccion')
            ->name('restricciones.destroy');
    });

    // CRUD de clientes - Solo con permisos
    Route::middleware(['permission:crear clientes'])->group(function () {
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    });
    
    Route::middleware(['permission:ver clientes'])->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])
            ->middleware('can:view,cliente')
            ->name('clientes.show');
    });

    Route::middleware(['permission:editar clientes'])->group(function () {
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])
            ->middleware('can:update,cliente')
            ->name('clientes.edit');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])
            ->middleware('can:update,cliente')
            ->name('clientes.update');
        Route::patch('/clientes/{cliente}', [ClienteController::class, 'update'])
            ->middleware('can:update,cliente');
    });

    Route::middleware(['permission:eliminar clientes'])->group(function () {
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])
            ->middleware('can:delete,cliente')
            ->name('clientes.destroy');
    });

    // CRUD de vehículos - Solo con permisos
    Route::middleware(['permission:crear vehículos'])->group(function () {
        Route::get('/vehiculos/create', [VehiculoController::class, 'create'])->name('vehiculos.create');
        Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
    });

    Route::middleware(['permission:ver vehículos'])->group(function () {
        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::get('/vehiculos/export/excel', [VehiculoController::class, 'export'])->name('vehiculos.export');
        Route::get('/vehiculos/export/pdf', [VehiculoController::class, 'exportPdf'])->name('vehiculos.exportPdf');
        Route::get('/vehiculos/{vehiculo}', [VehiculoController::class, 'show'])
            ->middleware('can:view,vehiculo')
            ->name('vehiculos.show');
    });

    Route::middleware(['permission:editar vehículos'])->group(function () {
        Route::get('/vehiculos/{vehiculo}/edit', [VehiculoController::class, 'edit'])
            ->middleware('can:update,vehiculo')
            ->name('vehiculos.edit');
        Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])
            ->middleware('can:update,vehiculo')
            ->name('vehiculos.update');
        Route::patch('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])
            ->middleware('can:update,vehiculo');
    });

    Route::middleware(['permission:eliminar vehículos'])->group(function () {
        Route::delete('/vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])
            ->middleware('can:delete,vehiculo')
            ->name('vehiculos.destroy');
    });

    // CRUD de ofertas - Solo con permisos
    Route::middleware(['permission:crear ofertas'])->group(function () {
        Route::get('/ofertas/create', [OfertaController::class, 'create'])->name('ofertas.create');
        Route::post('/ofertas', [OfertaController::class, 'store'])->name('ofertas.store');
    });
    
    Route::middleware(['permission:ver ofertas'])->group(function () {
        Route::get('/ofertas', [OfertaController::class, 'index'])->name('ofertas.index');
        Route::get('/ofertas/{oferta}', [OfertaController::class, 'show'])
            ->middleware('can:view,oferta')
            ->name('ofertas.show');
    });

    Route::middleware(['permission:eliminar ofertas'])->group(function () {
        Route::delete('/ofertas/{oferta}', [OfertaController::class, 'destroy'])
            ->middleware('can:delete,oferta')
            ->name('ofertas.destroy');
    });
});
