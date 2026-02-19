@extends('layouts.app')

@section('title', 'Dashboard - VEXIS')

@section('content')
<div class="vx-page-header">
    <div>
        <h1 class="vx-page-title">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
        <p style="font-size: 13px; color: var(--vx-text-secondary); margin-top: 2px;">
            {{ Auth::user()->empresa->nombre }} · {{ Auth::user()->departamento->nombre }}
        </p>
    </div>
</div>

{{-- Info del usuario --}}
<div class="vx-card" style="margin-bottom: 20px;">
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
                <span style="font-weight: 600;">{{ Auth::user()->centro->nombre }}</span>
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

{{-- Accesos Rápidos --}}
<h3 style="font-size: 15px; font-weight: 700; margin-bottom: 16px; color: var(--vx-text);">
    <i class="bi bi-lightning" style="color: var(--vx-primary);"></i> Accesos Rápidos
</h3>

<div class="vx-grid vx-grid-4">
    @can('ver usuarios')
    <a href="{{ route('users.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(51,170,221,0.12); color: var(--vx-primary);">
            <i class="bi bi-people"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Usuarios</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Gestión de usuarios del sistema</div>
        </div>
    </a>
    @endcan

    @can('ver clientes')
    <a href="{{ route('clientes.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(46,204,113,0.12); color: var(--vx-success);">
            <i class="bi bi-person-lines-fill"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Clientes</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Base de datos de clientes</div>
        </div>
    </a>
    @endcan

    @can('ver vehículos')
    <a href="{{ route('vehiculos.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(243,156,18,0.12); color: var(--vx-warning);">
            <i class="bi bi-truck"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Vehículos</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Stock de vehículos</div>
        </div>
    </a>
    @endcan

    @can('ver ofertas')
    <a href="{{ route('ofertas.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(231,76,60,0.12); color: var(--vx-danger);">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Ofertas</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Ofertas comerciales</div>
        </div>
    </a>
    @endcan

    @can('ver departamentos')
    <a href="{{ route('departamentos.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(155,164,174,0.15); color: var(--vx-accent-dark);">
            <i class="bi bi-diagram-3"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Departamentos</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Estructura organizativa</div>
        </div>
    </a>
    @endcan

    @can('ver centros')
    <a href="{{ route('centros.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(52,152,219,0.12); color: var(--vx-info);">
            <i class="bi bi-geo-alt"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Centros</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Concesionarios y sedes</div>
        </div>
    </a>
    @endcan

    @can('ver roles')
    <a href="{{ route('roles.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(142,68,173,0.12); color: #8E44AD;">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Roles</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Roles y permisos</div>
        </div>
    </a>
    @endcan

    @can('ver restricciones')
    <a href="{{ route('restricciones.index') }}" class="vx-stat-card">
        <div class="vx-stat-icon" style="background: rgba(230,126,34,0.12); color: #E67E22;">
            <i class="bi bi-lock"></i>
        </div>
        <div class="vx-stat-content">
            <h4>Restricciones</h4>
            <div style="font-size: 13px; color: var(--vx-text-secondary);">Control de acceso por datos</div>
        </div>
    </a>
    @endcan
</div>
@endsection
