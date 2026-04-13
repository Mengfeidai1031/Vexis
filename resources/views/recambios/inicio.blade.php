@extends('layouts.app')
@section('title', 'Recambios - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Módulo de Recambios</h1>
</div>

<div class="mod-banner">
    <div class="mod-banner-content">
        <div class="mod-banner-icon" style="background:linear-gradient(135deg,#1ABC9C,#16A085);"><i class="bi bi-box-seam"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;">Centro de Recambios</h2>
            <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Gestión de almacenes, stock y repartos de recambios.</p>
        </div>
    </div>
</div>

<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-boxes"></i> Operaciones</h3>
    <div class="mod-grid">
        @can('ver almacenes')
        <a href="{{ route('almacenes.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2980b9);"><i class="bi bi-boxes"></i></div>
            <div class="mod-card-info"><h4>Almacenes</h4><p>Gestión de almacenes por isla</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver stocks')
        <a href="{{ route('stocks.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-box2"></i></div>
            <div class="mod-card-info"><h4>Stock</h4><p>Inventario de recambios por almacén</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
        @can('ver repartos')
        <a href="{{ route('repartos.index') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-truck"></i></div>
            <div class="mod-card-info"><h4>Repartos</h4><p>Gestión de repartos entre almacenes</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        @endcan
    </div>
</div>
@endsection
