@extends('layouts.app')
@section('title', 'Recambios - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-box-seam" style="color: var(--vx-primary); margin-right: 8px;"></i>Módulo de Recambios</h1>
</div>
<p style="color: var(--vx-text-muted); margin-bottom: 24px;">Gestión de almacenes, stock y repartos de recambios.</p>

<div class="vx-module-section">
    <h3 class="vx-module-section-title">Operaciones</h3>
    <div class="vx-module-grid">
        @can('ver almacenes')
        <a href="{{ route('almacenes.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(51,170,221,0.1); color: var(--vx-primary);"><i class="bi bi-boxes"></i></div>
            <div class="vx-module-info"><h4>Almacenes</h4><p>Gestión de almacenes por isla</p></div>
        </a>
        @endcan
        @can('ver stocks')
        <a href="{{ route('stocks.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(46,204,113,0.1); color: var(--vx-success);"><i class="bi bi-box2"></i></div>
            <div class="vx-module-info"><h4>Stock</h4><p>Inventario de recambios por almacén</p></div>
        </a>
        @endcan
        @can('ver repartos')
        <a href="{{ route('repartos.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(243,156,18,0.1); color: var(--vx-warning);"><i class="bi bi-truck"></i></div>
            <div class="vx-module-info"><h4>Repartos</h4><p>Gestión de repartos entre almacenes</p></div>
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
.vx-module-card-disabled { opacity: 0.55; pointer-events: none; }
.vx-module-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.vx-module-info h4 { font-size: 14px; font-weight: 700; margin: 0 0 2px; }
.vx-module-info p { font-size: 12px; color: var(--vx-text-muted); margin: 0; }
.vx-module-soon { position: absolute; top: 8px; right: 10px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--vx-text-muted); background: var(--vx-gray-100); padding: 2px 6px; border-radius: 4px; }
</style>
@endpush
@endsection
