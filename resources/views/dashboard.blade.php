@extends('layouts.app')
@section('title', 'Dashboard - VEXIS')
@section('content')
<div class="vx-page-header">
    <div>
        <h1 class="vx-page-title">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
        <p style="font-size: 13px; color: var(--vx-text-secondary); margin-top: 2px;">
            {{ Auth::user()->empresa->nombre ?? '' }} · {{ Auth::user()->departamento->nombre ?? '' }}
        </p>
    </div>
</div>

{{-- Info del usuario --}}
<div class="vx-card" style="margin-bottom: 24px;">
    <div class="vx-card-body" style="display: flex; gap: 32px; flex-wrap: wrap; align-items: center;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div class="vx-avatar" style="width: 52px; height: 52px; font-size: 18px; cursor: default;">
                {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellidos, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight: 700; font-size: 16px;">{{ Auth::user()->nombre_completo }}</div>
                <div style="font-size: 12px; color: var(--vx-text-muted);">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <div style="display: flex; gap: 24px; flex-wrap: wrap; font-size: 13px;">
            <div>
                <span style="color: var(--vx-text-muted);">Centro:</span>
                <span style="font-weight: 600;">{{ Auth::user()->centro->nombre ?? '—' }}</span>
            </div>
            <div>
                <span style="color: var(--vx-text-muted);">Roles:</span>
                @foreach(Auth::user()->roles as $role)
                    <span class="vx-badge vx-badge-primary">{{ $role->name }}</span>
                @endforeach
            </div>
            <div>
                <span style="color: var(--vx-text-muted);">Permisos:</span>
                <span class="vx-badge vx-badge-info">{{ Auth::user()->getAllPermissions()->count() }} activos</span>
            </div>
        </div>
    </div>
</div>

{{-- Módulos --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-grid-1x2"></i> Módulos</h3>
    <div class="mod-grid">
        @canany(['ver usuarios', 'ver departamentos', 'ver centros', 'ver roles', 'ver restricciones', 'ver clientes'])
        <a href="{{ route('gestion.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2890BB);"><i class="bi bi-building"></i></div>
            <div class="mod-card-info"><h4>Gestión</h4><p>Usuarios, Clientes, Seguridad, Mantenimiento</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcanany

        @canany(['ver vehículos', 'ver ofertas'])
        <a href="{{ route('comercial.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-car-front"></i></div>
            <div class="mod-card-info"><h4>Comercial</h4><p>Ofertas, Vehículos, Ventas, Tasaciones</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcanany

        @can('ver almacenes')
        <a href="{{ route('recambios.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#1ABC9C,#16A085);"><i class="bi bi-box-seam"></i></div>
            <div class="mod-card-info"><h4>Recambios</h4><p>Almacenes, Stock, Repartos</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan

        @canany(['ver talleres', 'ver citas', 'ver coches-sustitucion'])
        <a href="{{ route('talleres.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#8E44AD,#9B59B6);"><i class="bi bi-wrench-adjustable"></i></div>
            <div class="mod-card-info"><h4>Talleres</h4><p>Talleres, Citas, Coches de sustitución</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcanany

        <a href="{{ route('dataxis.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-bar-chart-line"></i></div>
            <div class="mod-card-info"><h4>Dataxis</h4><p>Análisis, KPIs, Informes</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>

        <a href="{{ route('cliente.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-person-heart"></i></div>
            <div class="mod-card-info"><h4>Cliente</h4><p>Chatbot IA, Pretasación, Configurador</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
    </div>
</div>

{{-- Accesos Rápidos --}}
<div class="mod-section">
<h3 class="mod-section-title"><i class="bi bi-lightning"></i> Accesos Rápidos</h3>

<div class="vx-grid vx-grid-4">
    @can('ver usuarios')
    <a href="{{ route('users.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(51,170,221,0.12); color: var(--vx-primary);"><i class="bi bi-people"></i></div>
        <div class="vx-stat-content"><h4>Usuarios</h4></div>
    </a>
    @endcan
    @can('ver clientes')
    <a href="{{ route('clientes.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(46,204,113,0.12); color: var(--vx-success);"><i class="bi bi-person-lines-fill"></i></div>
        <div class="vx-stat-content"><h4>Clientes</h4></div>
    </a>
    @endcan
    @can('ver vehículos')
    <a href="{{ route('vehiculos.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(243,156,18,0.12); color: var(--vx-warning);"><i class="bi bi-truck"></i></div>
        <div class="vx-stat-content"><h4>Vehículos</h4></div>
    </a>
    @endcan
    @can('ver ofertas')
    <a href="{{ route('ofertas.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(231,76,60,0.12); color: var(--vx-danger);"><i class="bi bi-file-earmark-text"></i></div>
        <div class="vx-stat-content"><h4>Ofertas</h4></div>
    </a>
    @endcan
</div>
</div>
@endsection
