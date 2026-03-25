@extends('layouts.app')
@section('title', 'Área Cliente - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-person-heart" style="color:var(--vx-primary);margin-right:8px;"></i>Área de Cliente</h1>
</div>
<p style="color:var(--vx-text-muted);margin-bottom:24px;">Explora nuestro catálogo, configura tu vehículo y utiliza nuestro asistente inteligente.</p>
<div class="vx-module-section">
    <h3 class="vx-module-section-title">Servicios</h3>
    <div class="vx-module-grid">
        <a href="{{ route('cliente.chatbot') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);"><i class="bi bi-robot"></i></div>
            <div class="vx-module-info"><h4>Chatbot IA</h4><p>Pregunta sobre stock, precios y disponibilidad</p></div>
        </a>
        <a href="{{ route('cliente.pretasacion') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(155,89,182,0.1);color:#9B59B6;"><i class="bi bi-calculator"></i></div>
            <div class="vx-module-info"><h4>Pretasación IA</h4><p>Obtén una valoración orientativa de tu vehículo</p></div>
        </a>
        <a href="{{ route('cliente.tasacion') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(241,196,15,0.1);color:#F1C40F;"><i class="bi bi-clipboard-check"></i></div>
            <div class="vx-module-info"><h4>Tasación Formal</h4><p>Solicita una tasación oficial y consulta su estado</p></div>
        </a>
        <a href="{{ route('cliente.configurador') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(46,204,113,0.1);color:var(--vx-success);"><i class="bi bi-palette"></i></div>
            <div class="vx-module-info"><h4>Configurador</h4><p>Visualiza vehículos por color y perspectiva</p></div>
        </a>
        <a href="{{ route('cliente.precios') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(231,76,60,0.1);color:var(--vx-danger);"><i class="bi bi-currency-euro"></i></div>
            <div class="vx-module-info"><h4>Lista de Precios</h4><p>Catálogo completo con precios actualizados</p></div>
        </a>
        <a href="{{ route('cliente.campanias') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(243,156,18,0.1);color:var(--vx-warning);"><i class="bi bi-megaphone"></i></div>
            <div class="vx-module-info"><h4>Campañas</h4><p>Ofertas y promociones actuales</p></div>
        </a>
        <a href="{{ route('cliente.concesionarios') }}" class="vx-module-card">
            <div class="vx-module-icon" style="background:rgba(52,73,94,0.1);color:#34495E;"><i class="bi bi-building"></i></div>
            <div class="vx-module-info"><h4>Concesionarios</h4><p>Encuentra tu concesionario más cercano</p></div>
        </a>
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
