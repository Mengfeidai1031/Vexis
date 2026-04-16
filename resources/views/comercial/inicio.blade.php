@extends('layouts.app')
@section('title', 'Comercial - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Módulo Comercial</h1>
</div>

<div class="mod-banner">
    <div class="mod-banner-content">
        <div class="mod-banner-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-car-front"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;">Centro Comercial</h2>
            <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Gestión de ofertas, vehículos, ventas y tasaciones.</p>
        </div>
    </div>
</div>

{{-- Gestión Administrativa --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-clipboard-data"></i> Gestión Administrativa</h3>
    <div class="mod-grid">
        @can('ver ofertas')
        <a href="{{ route('ofertas.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-file-earmark-text"></i></div>
            <div class="mod-card-info"><h4>Ofertas</h4><p>Ofertas comerciales con procesamiento PDF</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver tasaciones')
        <a href="{{ route('tasaciones.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#9B59B6,#8E44AD);"><i class="bi bi-calculator"></i></div>
            <div class="mod-card-info"><h4>Tasaciones</h4><p>Tasaciones de vehículos</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
    </div>
</div>

{{-- Gestión Ventas --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-cart-check"></i> Gestión Ventas</h3>
    <div class="mod-grid">
        @can('ver ventas')
        <a href="{{ route('ventas.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-cart-check"></i></div>
            <div class="mod-card-info"><h4>Ventas</h4><p>Registro y seguimiento de ventas</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @if(\App\Models\Setting::get('modulo_facturas', true))
        @can('ver facturas')
        <a href="{{ route('facturas.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-receipt"></i></div>
            <div class="mod-card-info"><h4>Facturas</h4><p>Gestión y emisión de facturas</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @endif
        @if(\App\Models\Setting::get('modulo_verifactu', true))
        @can('ver verifactu')
        <a href="{{ route('verifactu.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#34495E,#2C3E50);"><i class="bi bi-shield-check"></i></div>
            <div class="mod-card-info"><h4>Verifactu</h4><p>Registro y verificación de facturación electrónica</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @endif
    </div>
</div>

{{-- Gestión de Vehículos --}}
<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-truck"></i> Gestión de Vehículos</h3>
    <div class="mod-grid">
        @can('ver vehículos')
        <a href="{{ route('vehiculos.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#3498DB,#2980b9);"><i class="bi bi-truck"></i></div>
            <div class="mod-card-info"><h4>Vehículos</h4><p>Inventario de vehículos con exportación</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver catalogo-precios')
        <a href="{{ route('catalogo-precios.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-currency-euro"></i></div>
            <div class="mod-card-info"><h4>Catálogo</h4><p>Modelos, versiones y precios base por marca</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
    </div>
</div>
@endsection
