@extends('layouts.app')
@section('title', 'Comercial - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-car-front" style="color: var(--vx-primary); margin-right: 8px;"></i>Módulo Comercial</h1>
</div>
<p style="color: var(--vx-text-muted); margin-bottom: 24px;">Gestión de ofertas, vehículos, ventas y tasaciones.</p>

<div class="vx-module-section">
    <h3 class="vx-module-section-title">Operaciones</h3>
    <div class="vx-module-grid">
        @can('ver ofertas')
        <a href="{{ route('ofertas.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(243,156,18,0.1); color: var(--vx-warning);"><i class="bi bi-file-earmark-text"></i></div>
            <div class="vx-module-info"><h4>Ofertas</h4><p>Ofertas comerciales con procesamiento PDF</p></div>
        </a>
        @endcan
        @can('ver vehículos')
        <a href="{{ route('vehiculos.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(52,152,219,0.1); color: var(--vx-info);"><i class="bi bi-truck"></i></div>
            <div class="vx-module-info"><h4>Vehículos</h4><p>Inventario de vehículos con exportación</p></div>
        </a>
        @endcan
        @can('ver ventas')
        <a href="{{ route('ventas.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(46,204,113,0.1); color: var(--vx-success);"><i class="bi bi-cart-check"></i></div>
            <div class="vx-module-info"><h4>Ventas</h4><p>Registro y seguimiento de ventas</p></div>
        </a>
        @endcan
        @can('ver tasaciones')
        <a href="{{ route('tasaciones.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(155,89,182,0.1); color: #9B59B6;"><i class="bi bi-calculator"></i></div>
            <div class="vx-module-info"><h4>Tasaciones</h4><p>Tasaciones de vehículos</p></div>
        </a>
        @endcan
        @can('ver catalogo-precios')
        <a href="{{ route('catalogo-precios.index') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background: rgba(231,76,60,0.1); color: var(--vx-danger);"><i class="bi bi-currency-euro"></i></div>
            <div class="vx-module-info"><h4>Catálogo de Precios</h4><p>Gestión de precios por vehículo y marca</p></div>
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
