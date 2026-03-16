@extends('layouts.app')
@section('title', 'Dataxis - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-graph-up" style="color:var(--vx-primary);margin-right:8px;"></i>Dataxis — Análisis de Datos</h1>
</div>
<p style="color:var(--vx-text-muted);margin-bottom:24px;">Panel de estadísticas y visualización de datos del negocio.</p>
<div class="vx-module-section">
    <h3 class="vx-module-section-title">Informes</h3>
    <div class="vx-module-grid">
        <a href="{{ route('dataxis.general') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-speedometer2"></i></div>
            <div class="vx-module-info"><h4>General</h4><p>KPIs, catálogo y crecimiento de clientes</p></div>
        </a>
        <a href="{{ route('dataxis.ventas') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-currency-euro"></i></div>
            <div class="vx-module-info"><h4>Ventas</h4><p>Rendimiento por mes, marca y vendedor</p></div>
        </a>
        <a href="{{ route('dataxis.stock') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-box-seam"></i></div>
            <div class="vx-module-info"><h4>Stock</h4><p>Inventario, valor y alertas de bajo stock</p></div>
        </a>
        <a href="{{ route('dataxis.taller') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(155,89,182,0.1);color:#9B59B6;"><i class="bi bi-wrench-adjustable"></i></div>
            <div class="vx-module-info"><h4>Taller</h4><p>Citas, carga mecánicos y tasaciones</p></div>
        </a>
    </div>
</div>

@push('styles')
<style>
.vx-module-section { margin-bottom: 28px; }
.vx-module-section-title { font-size: 15px; font-weight: 700; color: var(--vx-text-secondary); margin-bottom: 12px; }
.vx-module-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; }
.vx-module-card { display: flex; align-items: center; gap: 14px; padding: 18px 20px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); text-decoration: none; color: var(--vx-text); transition: all 0.2s; }
.vx-module-card:hover { border-color: var(--vx-primary); box-shadow: 0 4px 16px rgba(51,170,221,0.1); transform: translateY(-2px); }
.vx-module-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.vx-module-info h4 { font-size: 14px; font-weight: 700; margin: 0 0 2px; }
.vx-module-info p { font-size: 12px; color: var(--vx-text-muted); margin: 0; }
</style>
@endpush
@endsection
