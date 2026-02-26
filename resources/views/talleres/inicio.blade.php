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

@push('styles')
<style>
.vx-module-section { margin-bottom: 28px; }
.vx-module-section-title { font-size: 15px; font-weight: 700; color: var(--vx-text-secondary); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
.vx-module-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; }
.vx-module-card { display: flex; align-items: center; gap: 14px; padding: 18px 20px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); text-decoration: none; color: var(--vx-text); transition: all 0.2s; position: relative; }
.vx-module-card:hover { border-color: var(--vx-primary); box-shadow: 0 4px 16px rgba(51,170,221,0.1); transform: translateY(-2px); }
.vx-module-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.vx-module-info h4 { font-size: 14px; font-weight: 700; margin: 0 0 2px; }
.vx-module-info p { font-size: 12px; color: var(--vx-text-muted); margin: 0; }
</style>
@endpush
@endsection
