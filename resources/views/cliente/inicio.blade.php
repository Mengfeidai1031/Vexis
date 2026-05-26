@extends('layouts.app')
@section('title', 'Área Cliente - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Área de Cliente</h1>
</div>

{{-- Welcome banner --}}
<div class="cli-banner">
    <div class="cli-banner-content">
        <div class="cli-banner-icon"><i class="bi bi-person-heart"></i></div>
        <div>
            <h2 style="margin:0 0 4px;font-size:20px;font-weight:800;">Bienvenido{{ Auth::user() ? ', ' . Auth::user()->nombre : '' }}</h2>
            <p style="margin:0;font-size:13px;opacity:0.85;">Explora nuestro catálogo, configura tu vehículo y utiliza nuestro asistente inteligente.</p>
        </div>
    </div>
</div>

{{-- AI Services --}}
<div class="cli-section">
    <h3 class="cli-section-title"><i class="bi bi-stars"></i> Inteligencia Artificial</h3>
    <div class="cli-grid">
        <a href="{{ route('cliente.chatbot') }}" class="cli-card cli-card-featured">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#33AADD,#2980b9);"><i class="bi bi-robot"></i></div>
            <div class="cli-card-info">
                <h4>Chatbot IA</h4>
                <p>Pregunta sobre stock, precios y disponibilidad</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.pretasacion') }}" class="cli-card cli-card-featured">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#9B59B6,#8E44AD);"><i class="bi bi-calculator"></i></div>
            <div class="cli-card-info">
                <h4>Pretasación IA</h4>
                <p>Obtén una valoración orientativa de tu vehículo</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
    </div>
</div>

{{-- Vehicle Services --}}
<div class="cli-section">
    <h3 class="cli-section-title"><i class="bi bi-car-front"></i> Vehículos</h3>
    <div class="cli-grid">
        <a href="{{ route('cliente.configurador') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#2ECC71,#27AE60);"><i class="bi bi-palette"></i></div>
            <div class="cli-card-info">
                <h4>Configurador</h4>
                <p>Visualiza vehículos por color y perspectiva</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.precios') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#E74C3C,#C0392B);"><i class="bi bi-currency-euro"></i></div>
            <div class="cli-card-info">
                <h4>Lista de Precios</h4>
                <p>Catálogo completo con precios actualizados</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.tasacion') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#F1C40F,#F39C12);"><i class="bi bi-clipboard-check"></i></div>
            <div class="cli-card-info">
                <h4>Tasación Formal</h4>
                <p>Solicita una tasación oficial y consulta su estado</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
    </div>
</div>

{{-- Info Services --}}
<div class="cli-section">
    <h3 class="cli-section-title"><i class="bi bi-info-circle"></i> Información</h3>
    <div class="cli-grid">
        <a href="{{ route('cliente.campanias') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#F39C12,#E67E22);"><i class="bi bi-megaphone"></i></div>
            <div class="cli-card-info">
                <h4>Campañas</h4>
                <p>Ofertas y promociones actuales</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.concesionarios') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#34495E,#2C3E50);"><i class="bi bi-building"></i></div>
            <div class="cli-card-info">
                <h4>Concesionarios</h4>
                <p>Encuentra tu concesionario más cercano</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.noticias') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#2980b9,#3498DB);"><i class="bi bi-newspaper"></i></div>
            <div class="cli-card-info">
                <h4>Noticias</h4>
                <p>Últimas novedades del grupo</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
        <a href="{{ route('cliente.talleres') }}" class="cli-card">
            <div class="cli-card-icon" style="background:linear-gradient(135deg,#E67E22,#D35400);"><i class="bi bi-tools"></i></div>
            <div class="cli-card-info">
                <h4>Talleres</h4>
                <p>Encuentra el taller más cercano</p>
            </div>
            <i class="bi bi-arrow-right cli-card-arrow"></i>
        </a>
    </div>
</div>

@push('styles')
<style>
.cli-banner{background:linear-gradient(135deg,rgba(51,170,221,0.08),rgba(46,204,113,0.06));border:1px solid var(--vx-border);border-radius:var(--vx-radius-lg);padding:28px 32px;margin-bottom:28px;}
.cli-banner-content{display:flex;align-items:center;gap:16px;}
.cli-banner-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--vx-primary),#2ECC71);display:flex;align-items:center;justify-content:center;font-size:24px;color:white;flex-shrink:0;box-shadow:0 4px 16px rgba(51,170,221,0.25);}
.cli-section{margin-bottom:28px;}
.cli-section-title{font-size:14px;font-weight:700;color:var(--vx-text-muted);margin-bottom:14px;display:flex;align-items:center;gap:8px;letter-spacing:0.3px;}
.cli-section-title i{font-size:16px;color:var(--vx-primary);}
.cli-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;}
.cli-card{display:flex;align-items:center;gap:14px;padding:18px 20px;background:var(--vx-surface);border:1px solid var(--vx-border);border-radius:var(--vx-radius-lg);text-decoration:none;color:var(--vx-text);transition:all 0.25s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden;}
.cli-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent,rgba(51,170,221,0.02));opacity:0;transition:opacity 0.25s;}
.cli-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,0.1);border-color:var(--vx-primary);}
.cli-card:hover::before{opacity:1;}
.cli-card:hover .cli-card-arrow{opacity:1;transform:translateX(0);}
.cli-card-featured{border-left:3px solid transparent;}
.cli-card-featured:hover{border-left-color:var(--vx-primary);}
.cli-card-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:white;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.12);}
.cli-card-info h4{font-size:14px;font-weight:700;margin:0 0 2px;}
.cli-card-info p{font-size:12px;color:var(--vx-text-muted);margin:0;line-height:1.4;}
.cli-card-arrow{margin-left:auto;font-size:16px;color:var(--vx-primary);opacity:0;transform:translateX(-8px);transition:all 0.25s;}
</style>
@endpush
@endsection
