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
use App\Http\Controllers\VacacionController;
use App\Http\Controllers\FestivoController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RepartoController;
use App\Http\Controllers\TallerController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\CitaTallerController;
use App\Http\Controllers\CocheSustitucionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TasacionController;
use App\Http\Controllers\CatalogoPrecioController;
use App\Http\Controllers\ClienteModuloController;
use App\Http\Controllers\DatAxisController;
use App\Http\Controllers\AlmacenController;

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

    // Vacaciones
    Route::get('/vacaciones', [VacacionController::class, 'index'])->name('vacaciones.index');
    Route::get('/vacaciones/create', [VacacionController::class, 'create'])->name('vacaciones.create');
    Route::post('/vacaciones', [VacacionController::class, 'store'])->name('vacaciones.store');
    Route::patch('/vacaciones/{vacacion}/gestionar', [VacacionController::class, 'gestionar'])->name('vacaciones.gestionar');
    Route::delete('/vacaciones/{vacacion}', [VacacionController::class, 'destroy'])->name('vacaciones.destroy');

    // CRUD de Festivos
    Route::middleware(['permission:crear festivos'])->group(function () {
        Route::get('/festivos/create', [FestivoController::class, 'create'])->name('festivos.create');
        Route::post('/festivos', [FestivoController::class, 'store'])->name('festivos.store');
    });
    Route::middleware(['permission:ver festivos'])->group(function () {
        Route::get('/festivos', [FestivoController::class, 'index'])->name('festivos.index');
    });
    Route::middleware(['permission:editar festivos'])->group(function () {
        Route::get('/festivos/{festivo}/edit', [FestivoController::class, 'edit'])->name('festivos.edit');
        Route::put('/festivos/{festivo}', [FestivoController::class, 'update'])->name('festivos.update');
    });
    Route::middleware(['permission:eliminar festivos'])->group(function () {
        Route::delete('/festivos/{festivo}', [FestivoController::class, 'destroy'])->name('festivos.destroy');
    });

    // Módulo Recambios - Inicio
    Route::get('/recambios', function () { return view('recambios.inicio'); })->name('recambios.inicio');

    // CRUD de Almacenes
    Route::middleware(['permission:crear almacenes'])->group(function () {
        Route::get('/almacenes/create', [AlmacenController::class, 'create'])->name('almacenes.create');
        Route::post('/almacenes', [AlmacenController::class, 'store'])->name('almacenes.store');
    });
    Route::middleware(['permission:ver almacenes'])->group(function () {
        Route::get('/almacenes', [AlmacenController::class, 'index'])->name('almacenes.index');
        Route::get('/almacenes/{almacen}', [AlmacenController::class, 'show'])->name('almacenes.show');
    });
    Route::middleware(['permission:editar almacenes'])->group(function () {
        Route::get('/almacenes/{almacen}/edit', [AlmacenController::class, 'edit'])->name('almacenes.edit');
        Route::put('/almacenes/{almacen}', [AlmacenController::class, 'update'])->name('almacenes.update');
    });
    Route::middleware(['permission:eliminar almacenes'])->group(function () {
        Route::delete('/almacenes/{almacen}', [AlmacenController::class, 'destroy'])->name('almacenes.destroy');
    });

    // CRUD de Stock
    Route::middleware(['permission:crear stocks'])->group(function () {
        Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
        Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
    });
    Route::middleware(['permission:ver stocks'])->group(function () {
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/export/excel', [StockController::class, 'export'])->name('stocks.export');
        Route::get('/stocks/export/pdf', [StockController::class, 'exportPdf'])->name('stocks.exportPdf');
        Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
    });
    Route::middleware(['permission:editar stocks'])->group(function () {
        Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
        Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
    });
    Route::middleware(['permission:eliminar stocks'])->group(function () {
        Route::delete('/stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');
    });

    // CRUD de Repartos
    Route::middleware(['permission:crear repartos'])->group(function () {
        Route::get('/repartos/create', [RepartoController::class, 'create'])->name('repartos.create');
        Route::post('/repartos', [RepartoController::class, 'store'])->name('repartos.store');
    });
    Route::middleware(['permission:ver repartos'])->group(function () {
        Route::get('/repartos', [RepartoController::class, 'index'])->name('repartos.index');
        Route::get('/repartos/{reparto}', [RepartoController::class, 'show'])->name('repartos.show');
    });
    Route::middleware(['permission:editar repartos'])->group(function () {
        Route::get('/repartos/{reparto}/edit', [RepartoController::class, 'edit'])->name('repartos.edit');
        Route::put('/repartos/{reparto}', [RepartoController::class, 'update'])->name('repartos.update');
    });
    Route::middleware(['permission:eliminar repartos'])->group(function () {
        Route::delete('/repartos/{reparto}', [RepartoController::class, 'destroy'])->name('repartos.destroy');
    });

    // === MÓDULO TALLERES ===
    Route::get('/talleres-modulo', function () { return view('talleres.inicio'); })->name('talleres.inicio');

    // CRUD Talleres
    Route::middleware(['permission:crear talleres'])->group(function () {
        Route::get('/talleres/create', [TallerController::class, 'create'])->name('talleres.create');
        Route::post('/talleres', [TallerController::class, 'store'])->name('talleres.store');
    });
    Route::middleware(['permission:ver talleres'])->group(function () {
        Route::get('/talleres', [TallerController::class, 'index'])->name('talleres.index');
        Route::get('/talleres/{taller}', [TallerController::class, 'show'])->name('talleres.show');
    });
    Route::middleware(['permission:editar talleres'])->group(function () {
        Route::get('/talleres/{taller}/edit', [TallerController::class, 'edit'])->name('talleres.edit');
        Route::put('/talleres/{taller}', [TallerController::class, 'update'])->name('talleres.update');
    });
    Route::middleware(['permission:eliminar talleres'])->group(function () {
        Route::delete('/talleres/{taller}', [TallerController::class, 'destroy'])->name('talleres.destroy');
    });

    // CRUD Mecánicos
    Route::middleware(['permission:crear mecanicos'])->group(function () {
        Route::get('/mecanicos/create', [MecanicoController::class, 'create'])->name('mecanicos.create');
        Route::post('/mecanicos', [MecanicoController::class, 'store'])->name('mecanicos.store');
    });
    Route::middleware(['permission:ver mecanicos'])->group(function () {
        Route::get('/mecanicos', [MecanicoController::class, 'index'])->name('mecanicos.index');
    });
    Route::middleware(['permission:editar mecanicos'])->group(function () {
        Route::get('/mecanicos/{mecanico}/edit', [MecanicoController::class, 'edit'])->name('mecanicos.edit');
        Route::put('/mecanicos/{mecanico}', [MecanicoController::class, 'update'])->name('mecanicos.update');
    });
    Route::middleware(['permission:eliminar mecanicos'])->group(function () {
        Route::delete('/mecanicos/{mecanico}', [MecanicoController::class, 'destroy'])->name('mecanicos.destroy');
    });

    // CRUD Citas Taller
    Route::middleware(['permission:crear citas'])->group(function () {
        Route::get('/citas/create', [CitaTallerController::class, 'create'])->name('citas.create');
        Route::post('/citas', [CitaTallerController::class, 'store'])->name('citas.store');
    });
    Route::middleware(['permission:ver citas'])->group(function () {
        Route::get('/citas', [CitaTallerController::class, 'index'])->name('citas.index');
    });
    Route::middleware(['permission:editar citas'])->group(function () {
        Route::get('/citas/{cita}/edit', [CitaTallerController::class, 'edit'])->name('citas.edit');
        Route::put('/citas/{cita}', [CitaTallerController::class, 'update'])->name('citas.update');
    });
    Route::middleware(['permission:eliminar citas'])->group(function () {
        Route::delete('/citas/{cita}', [CitaTallerController::class, 'destroy'])->name('citas.destroy');
    });

    // CRUD Coches Sustitución
    Route::middleware(['permission:crear coches-sustitucion'])->group(function () {
        Route::get('/coches-sustitucion/create', [CocheSustitucionController::class, 'create'])->name('coches-sustitucion.create');
        Route::post('/coches-sustitucion', [CocheSustitucionController::class, 'store'])->name('coches-sustitucion.store');
    });
    Route::middleware(['permission:ver coches-sustitucion'])->group(function () {
        Route::get('/coches-sustitucion', [CocheSustitucionController::class, 'index'])->name('coches-sustitucion.index');
        Route::get('/coches-sustitucion/{coches_sustitucion}', [CocheSustitucionController::class, 'show'])->name('coches-sustitucion.show');
    });
    Route::middleware(['permission:editar coches-sustitucion'])->group(function () {
        Route::get('/coches-sustitucion/{coches_sustitucion}/edit', [CocheSustitucionController::class, 'edit'])->name('coches-sustitucion.edit');
        Route::put('/coches-sustitucion/{coches_sustitucion}', [CocheSustitucionController::class, 'update'])->name('coches-sustitucion.update');
    });
    Route::middleware(['permission:eliminar coches-sustitucion'])->group(function () {
        Route::delete('/coches-sustitucion/{coches_sustitucion}', [CocheSustitucionController::class, 'destroy'])->name('coches-sustitucion.destroy');
    });
    Route::post('/coches-sustitucion/{coche}/reservar', [CocheSustitucionController::class, 'reservar'])->name('coches-sustitucion.reservar');

    // === MÓDULO COMERCIAL: Ventas ===
    Route::middleware(['permission:crear ventas'])->group(function () {
        Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    });
    Route::middleware(['permission:ver ventas'])->group(function () {
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    });
    Route::middleware(['permission:editar ventas'])->group(function () {
        Route::get('/ventas/{venta}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
        Route::put('/ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
    });
    Route::middleware(['permission:eliminar ventas'])->group(function () {
        Route::delete('/ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');
    });

    // Tasaciones
    Route::middleware(['permission:crear tasaciones'])->group(function () {
        Route::get('/tasaciones/create', [TasacionController::class, 'create'])->name('tasaciones.create');
        Route::post('/tasaciones', [TasacionController::class, 'store'])->name('tasaciones.store');
    });
    Route::middleware(['permission:ver tasaciones'])->group(function () {
        Route::get('/tasaciones', [TasacionController::class, 'index'])->name('tasaciones.index');
        Route::get('/tasaciones/{tasacion}', [TasacionController::class, 'show'])->name('tasaciones.show');
    });
    Route::middleware(['permission:editar tasaciones'])->group(function () {
        Route::get('/tasaciones/{tasacion}/edit', [TasacionController::class, 'edit'])->name('tasaciones.edit');
        Route::put('/tasaciones/{tasacion}', [TasacionController::class, 'update'])->name('tasaciones.update');
    });
    Route::middleware(['permission:eliminar tasaciones'])->group(function () {
        Route::delete('/tasaciones/{tasacion}', [TasacionController::class, 'destroy'])->name('tasaciones.destroy');
    });

    // Catálogo de Precios
    Route::middleware(['permission:crear catalogo-precios'])->group(function () {
        Route::get('/catalogo-precios/create', [CatalogoPrecioController::class, 'create'])->name('catalogo-precios.create');
        Route::post('/catalogo-precios', [CatalogoPrecioController::class, 'store'])->name('catalogo-precios.store');
    });
    Route::middleware(['permission:ver catalogo-precios'])->group(function () {
        Route::get('/catalogo-precios', [CatalogoPrecioController::class, 'index'])->name('catalogo-precios.index');
    });
    Route::middleware(['permission:editar catalogo-precios'])->group(function () {
        Route::get('/catalogo-precios/{catalogo_precio}/edit', [CatalogoPrecioController::class, 'edit'])->name('catalogo-precios.edit');
        Route::put('/catalogo-precios/{catalogo_precio}', [CatalogoPrecioController::class, 'update'])->name('catalogo-precios.update');
    });
    Route::middleware(['permission:eliminar catalogo-precios'])->group(function () {
        Route::delete('/catalogo-precios/{catalogo_precio}', [CatalogoPrecioController::class, 'destroy'])->name('catalogo-precios.destroy');
    });

    // === DATAXIS (Análisis de datos) ===
    Route::get('/dataxis', [DatAxisController::class, 'inicio'])->name('dataxis.inicio');
    Route::get('/dataxis/general', [DatAxisController::class, 'general'])->name('dataxis.general');
    Route::get('/dataxis/ventas', [DatAxisController::class, 'ventas'])->name('dataxis.ventas');
    Route::get('/dataxis/stock', [DatAxisController::class, 'stock'])->name('dataxis.stock');
    Route::get('/dataxis/taller', [DatAxisController::class, 'taller'])->name('dataxis.taller');

    // === MÓDULO CLIENTE ===
    Route::get('/cliente', [ClienteModuloController::class, 'inicio'])->name('cliente.inicio');
    Route::get('/cliente/chatbot', [ClienteModuloController::class, 'chatbot'])->name('cliente.chatbot');
    Route::post('/cliente/chatbot/query', [ClienteModuloController::class, 'chatbotQuery'])->name('cliente.chatbot.query');
    Route::get('/cliente/pretasacion', [ClienteModuloController::class, 'pretasacion'])->name('cliente.pretasacion');
    Route::post('/cliente/pretasacion/query', [ClienteModuloController::class, 'pretasacionQuery'])->name('cliente.pretasacion.query');
    Route::get('/cliente/tasacion', [ClienteModuloController::class, 'tasacion'])->name('cliente.tasacion');
    Route::post('/cliente/tasacion', [ClienteModuloController::class, 'tasacionStore'])->name('cliente.tasacion.store');
    Route::get('/cliente/campanias', [ClienteModuloController::class, 'campanias'])->name('cliente.campanias');
    Route::get('/cliente/concesionarios', [ClienteModuloController::class, 'concesionarios'])->name('cliente.concesionarios');
    Route::get('/cliente/precios', [ClienteModuloController::class, 'precios'])->name('cliente.precios');
    Route::get('/cliente/configurador', [ClienteModuloController::class, 'configurador'])->name('cliente.configurador');

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
