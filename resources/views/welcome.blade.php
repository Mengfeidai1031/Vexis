@extends('layouts.app')

@section('title', 'Bienvenido - VEXIS')

@push('styles')
<style>
    .vx-welcome {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - var(--vx-navbar-height) - 60px);
        text-align: center;
        padding: 40px 16px;
    }
    .vx-welcome-content { max-width: 500px; }
    .vx-welcome-logo { margin-bottom: 24px; }
    .vx-welcome-logo img { height: 52px; }
    .vx-welcome-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--vx-text);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .vx-welcome-desc {
        font-size: 15px;
        color: var(--vx-text-secondary);
        margin-bottom: 32px;
        line-height: 1.6;
    }
    .vx-welcome-separator {
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--vx-primary), var(--vx-accent));
        border-radius: 2px;
        margin: 0 auto 24px;
    }
    .vx-welcome-brands {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 32px;
        color: var(--vx-text-muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<div class="vx-welcome">
    <div class="vx-welcome-content">
        <div class="vx-welcome-logo">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS">
        </div>
        <div class="vx-welcome-separator"></div>
        <h1 class="vx-welcome-title">Sistema de Gestión</h1>
        <p class="vx-welcome-desc">
            Plataforma integral de gestión de concesionarios, clientes, vehículos y ofertas comerciales para Grupo ARI
        </p>

        @auth
            <a href="{{ route('dashboard') }}" class="vx-btn vx-btn-primary vx-btn-lg">
                <i class="bi bi-grid-1x2"></i> Ir al Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="vx-btn vx-btn-primary vx-btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
        @endauth

        <div class="vx-welcome-brands">
            <span>Nissan</span>
            <span>•</span>
            <span>Renault</span>
            <span>•</span>
            <span>Dacia</span>
        </div>
    </div>
</div>
@endsection
