@extends('layouts.app')
@section('title', 'Talleres - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-wrench-adjustable" style="color:var(--vx-primary);margin-right:8px;"></i>Módulo de Talleres</h1>
</div>
<p style="color:var(--vx-text-muted);margin-bottom:24px;">Gestión de talleres, mecánicos, citas y coches de sustitución.</p>
<div class="vx-module-section">
    <h3 class="vx-module-section-title">Operaciones</h3>
    <div class="vx-module-grid">
        @can('ver talleres')
        <a href="{{ route('talleres.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-tools"></i></div>
            <div class="vx-module-info"><h4>Talleres</h4><p>Gestión de talleres por isla y marca</p></div>
        </a>
        @endcan
        @can('ver mecanicos')
        <a href="{{ route('mecanicos.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-person-gear"></i></div>
            <div class="vx-module-info"><h4>Mecánicos</h4><p>Registro de mecánicos por taller</p></div>
        </a>
        @endcan
        @can('ver citas')
        <a href="{{ route('citas.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(155,89,182,0.1);color:#9B59B6;"><i class="bi bi-calendar-check"></i></div>
            <div class="vx-module-info"><h4>Citas</h4><p>Calendario de citas disponibles</p></div>
        </a>
        @endcan
        @can('ver coches-sustitucion')
        <a href="{{ route('coches-sustitucion.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-car-front"></i></div>
            <div class="vx-module-info"><h4>Coches de Sustitución</h4><p>Flota y calendario de reservas</p></div>
        </a>
        @endcan
    </div>
</div>
@endsection
