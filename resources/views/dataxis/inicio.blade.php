@extends('layouts.app')
@section('title', 'Dataxis - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Dataxis — Análisis de Datos</h1>
</div>

<div class="mod-banner">
    <div class="mod-banner-content">
        <div class="mod-banner-icon" style="background:linear-gradient(135deg,var(--vx-primary),#2ECC71);"><i class="bi bi-bar-chart-line"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;font-weight:800;">Centro de Análisis</h2>
            <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Selecciona un informe para explorar los datos del negocio</p>
        </div>
    </div>
</div>

<div class="mod-section">
    <h3 class="mod-section-title"><i class="bi bi-file-earmark-bar-graph"></i> Informes</h3>
    <div class="mod-grid">
        <a href="{{ route('dataxis.general') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#33AADD,#2980b9);"><i class="bi bi-speedometer2"></i></div>
            <div class="mod-card-info"><h4>General</h4><p>KPIs, catálogo y crecimiento de clientes</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        <a href="{{ route('dataxis.ventas') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-currency-euro"></i></div>
            <div class="mod-card-info"><h4>Ventas</h4><p>Rendimiento por mes, marca y vendedor</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        <a href="{{ route('dataxis.stock') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-box-seam"></i></div>
            <div class="mod-card-info"><h4>Stock</h4><p>Inventario, valor y alertas de bajo stock</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        <a href="{{ route('dataxis.taller') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#9B59B6,#8E44AD);"><i class="bi bi-wrench-adjustable"></i></div>
            <div class="mod-card-info"><h4>Taller</h4><p>Citas, carga mecánicos y tasaciones</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        <a href="{{ route('dataxis.facturas') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-receipt"></i></div>
            <div class="mod-card-info"><h4>Facturas</h4><p>Facturación mensual, por estado y marca</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
        <a href="{{ route('dataxis.incidencias') }}" class="mod-card">
            <div class="mod-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="mod-card-info"><h4>Incidencias</h4><p>Estado, prioridad, tiempos y carga técnica</p></div>
            <i class="bi bi-arrow-right mod-card-arrow"></i>
        </a>
    </div>
</div>
@endsection
