@extends('layouts.app')
@section('title', 'Gestión - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Módulo de Gestión</h1>
</div>

<div class="mod-banner">
    <div class="mod-banner-content">
        <div class="mod-banner-icon" style="background:linear-gradient(135deg,#33AADD,#2890BB);"><i class="bi bi-building"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;">Centro de Gestión</h2>
            <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Administración de usuarios, clientes, seguridad y mantenimiento del sistema.</p>
        </div>
    </div>
</div>

{{-- Principal --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-people"></i> Principal</h3>
    <div class="mod-grid">
        @can('ver usuarios')
        <a href="{{ route('users.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2980b9);"><i class="bi bi-people"></i></div>
            <div class="mod-card-info"><h4>Usuarios</h4><p>Gestión de usuarios del sistema</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver clientes')
        <a href="{{ route('clientes.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-person-lines-fill"></i></div>
            <div class="mod-card-info"><h4>Clientes</h4><p>Base de datos de clientes</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        <a href="{{ route('vacaciones.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E67E22,#D35400);"><i class="bi bi-calendar-check"></i></div>
            <div class="mod-card-info"><h4>Vacaciones</h4><p>Solicitud y calendario de vacaciones</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
    </div>
</div>

{{-- Seguridad --}}
@canany(['ver roles', 'ver restricciones'])
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-shield-lock"></i> Seguridad</h3>
    <div class="mod-grid">
        @can('ver roles')
        <a href="{{ route('roles.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#9B64D2,#8E44AD);"><i class="bi bi-shield-check"></i></div>
            <div class="mod-card-info"><h4>Roles</h4><p>Gestión de roles del sistema</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver roles')
        <a href="{{ route('gestion.permisos') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#3498DB,#2980b9);"><i class="bi bi-key"></i></div>
            <div class="mod-card-info"><h4>Permisos</h4><p>Matriz de permisos por rol</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver restricciones')
        <a href="{{ route('restricciones.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-lock"></i></div>
            <div class="mod-card-info"><h4>Restricciones</h4><p>Restricciones de acceso por entidad</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        <a href="{{ route('gestion.politica') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-file-earmark-lock"></i></div>
            <div class="mod-card-info"><h4>Política</h4><p>Política de seguridad del sistema</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @role('Super Admin')
        <a href="{{ route('logs.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E67E22,#C0392B);"><i class="bi bi-journal-text"></i></div>
            <div class="mod-card-info"><h4>Logs del sistema</h4><p>Monitor de errores y eventos de seguridad</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endrole
    </div>
</div>
@endcanany

{{-- Marketing --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-megaphone"></i> Marketing</h3>
    <div class="mod-grid">
        @can('ver noticias')
        <a href="{{ route('noticias.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#3498DB,#2980b9);"><i class="bi bi-newspaper"></i></div>
            <div class="mod-card-info"><h4>Noticias</h4><p>Noticias y comunicados internos</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver campanias')
        <a href="{{ route('campanias.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#9B59B6,#8E44AD);"><i class="bi bi-megaphone"></i></div>
            <div class="mod-card-info"><h4>Campañas</h4><p>Gestión de campañas publicitarias</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
    </div>
</div>

{{-- Mantenimiento --}}
@canany(['ver departamentos', 'ver centros'])
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-gear"></i> Mantenimiento</h3>
    <div class="mod-grid">
        @can('ver empresas')
        <a href="{{ route('empresas.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2890BB);"><i class="bi bi-building"></i></div>
            <div class="mod-card-info"><h4>Empresas</h4><p>Empresas del grupo</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver departamentos')
        <a href="{{ route('departamentos.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-diagram-3"></i></div>
            <div class="mod-card-info"><h4>Departamentos</h4><p>Departamentos de la organización</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver centros')
        <a href="{{ route('centros.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-geo-alt"></i></div>
            <div class="mod-card-info"><h4>Centros</h4><p>Centros de trabajo y ubicaciones</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        <a href="{{ route('gestion.marcas') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#646B52,#556B2F);"><i class="bi bi-tags"></i></div>
            <div class="mod-card-info"><h4>Marcas</h4><p>Marcas de vehículos gestionadas</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @can('ver naming-pcs')
        <a href="{{ route('naming-pcs.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#34495E,#2C3E50);"><i class="bi bi-pc-display"></i></div>
            <div class="mod-card-info"><h4>Naming PCs</h4><p>Nomenclatura de equipos informáticos</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver festivos')
        <a href="{{ route('festivos.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-calendar-event"></i></div>
            <div class="mod-card-info"><h4>Festivos</h4><p>Calendario de festivos por municipio</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @if(\App\Models\Setting::get('modulo_incidencias', true))
        @can('ver incidencias')
        <a href="{{ route('incidencias.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="mod-card-info"><h4>Incidencias</h4><p>Gestión de incidencias y soporte</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @endif
    </div>
</div>
@endcanany

{{-- Dataxis --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-graph-up"></i> Dataxis</h3>
    <div class="mod-grid">
        <a href="{{ route('dataxis.inicio') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-graph-up"></i></div>
            <div class="mod-card-info"><h4>Dataxis</h4><p>Análisis, estadísticas y gráficas de datos</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
    </div>
</div>
@endsection
