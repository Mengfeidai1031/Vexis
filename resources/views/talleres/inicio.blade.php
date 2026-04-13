@extends('layouts.app')
@section('title', 'Talleres - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Módulo de Talleres</h1>
</div>

<div class="mod-banner">
    <div class="mod-banner-content">
        <div class="mod-banner-icon" style="background:linear-gradient(135deg,#8E44AD,#9B59B6);"><i class="bi bi-wrench-adjustable"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;">Centro de Talleres</h2>
            <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Gestión de talleres, mecánicos, citas y coches de sustitución.</p>
        </div>
    </div>
</div>

<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-tools"></i> Operaciones</h3>
    <div class="mod-grid">
        @can('ver talleres')
        <a href="{{ route('talleres.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2980b9);"><i class="bi bi-tools"></i></div>
            <div class="mod-card-info"><h4>Talleres</h4><p>Gestión de talleres por isla y marca</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver mecanicos')
        <a href="{{ route('mecanicos.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-person-gear"></i></div>
            <div class="mod-card-info"><h4>Mecánicos</h4><p>Registro de mecánicos por taller</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver citas')
        <a href="{{ route('citas.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#9B59B6,#8E44AD);"><i class="bi bi-calendar-check"></i></div>
            <div class="mod-card-info"><h4>Citas</h4><p>Calendario de citas disponibles</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver coches-sustitucion')
        <a href="{{ route('coches-sustitucion.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-car-front"></i></div>
            <div class="mod-card-info"><h4>Coches de Sustitución</h4><p>Flota y calendario de reservas</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
    </div>
</div>
@endsection
