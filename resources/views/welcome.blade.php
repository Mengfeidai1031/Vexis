@extends('layouts.app')
@section('title', 'VEXIS - Grupo DAI')
@section('content')

@auth
{{-- === CON SESIÓN: Saludo + Accesos rápidos === --}}
<div style="max-width: 900px; margin: 0 auto;">
    <div style="text-align: center; padding: 40px 0 20px;">
        @php
            $hour = (int) now()->format('H');
            $greeting = $hour < 12 ? 'Buenos días' : ($hour < 20 ? 'Buenas tardes' : 'Buenas noches');
        @endphp
        <h1 style="font-size: 28px; font-weight: 300; color: var(--vx-text); margin-bottom: 4px;">
            {{ $greeting }}, <strong style="font-weight: 700;">{{ Auth::user()->nombre }}</strong>
        </h1>
        <p style="font-size: 14px; color: var(--vx-text-muted);">Bienvenido a VEXIS — Sistema de Gestión de Grupo DAI</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 20px;">
        @canany(['ver usuarios', 'ver departamentos', 'ver centros', 'ver roles', 'ver restricciones', 'ver clientes'])
        <a href="{{ route('gestion.inicio') }}" class="vx-quick-card">
            <i class="bi bi-building" style="color: var(--vx-primary);"></i>
            <span>Gestión</span>
        </a>
        @endcanany
        @canany(['ver vehículos', 'ver ofertas'])
        <a href="{{ route('comercial.inicio') }}" class="vx-quick-card">
            <i class="bi bi-car-front" style="color: var(--vx-warning);"></i>
            <span>Comercial</span>
        </a>
        @endcanany
        @can('ver almacenes')
        <a href="{{ route('recambios.inicio') }}" class="vx-quick-card">
            <i class="bi bi-box-seam" style="color: var(--vx-success);"></i>
            <span>Recambios</span>
        </a>
        @endcan
        @canany(['ver talleres', 'ver citas', 'ver coches-sustitucion'])
        <a href="{{ route('talleres.inicio') }}" class="vx-quick-card">
            <i class="bi bi-wrench-adjustable" style="color: var(--vx-accent-dark);"></i>
            <span>Talleres</span>
        </a>
        @endcanany
        <a href="{{ route('dataxis.inicio') }}" class="vx-quick-card">
            <i class="bi bi-bar-chart-line" style="color: #27AE60;"></i>
            <span>Dataxis</span>
        </a>
        <a href="{{ route('cliente.inicio') }}" class="vx-quick-card">
            <i class="bi bi-person-heart" style="color: var(--vx-danger);"></i>
            <span>Cliente</span>
        </a>
        <a href="{{ route('dashboard') }}" class="vx-quick-card">
            <i class="bi bi-speedometer2" style="color: var(--vx-primary-dark);"></i>
            <span>Dashboard</span>
        </a>
    </div>
</div>

<style>
.vx-quick-card { display: flex; align-items: center; gap: 12px; padding: 18px 20px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); text-decoration: none; color: var(--vx-text); transition: all 0.2s; }
.vx-quick-card:hover { border-color: var(--vx-primary); box-shadow: 0 4px 12px rgba(51,170,221,0.12); transform: translateY(-2px); }
.vx-quick-card i { font-size: 24px; }
.vx-quick-card span { font-size: 14px; font-weight: 600; }
</style>

@else
{{-- === SIN SESIÓN: Hero + Login/Registro === --}}
<div style="min-height: calc(100vh - var(--vx-navbar-height) - 60px); display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; max-width: 440px; padding: 40px 20px;">
        <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS" style="width: 180px; margin-bottom: 32px;">
        <h1 style="font-size: 22px; font-weight: 300; color: var(--vx-text); margin-bottom: 8px;">
            Sistema de Gestión
        </h1>
        <p style="font-size: 14px; color: var(--vx-text-muted); margin-bottom: 32px;">
            Plataforma integral de gestión para concesionarios — Grupo DAI
        </p>
        <div style="display: flex; flex-direction: column; gap: 10px; max-width: 280px; margin: 0 auto;">
            <a href="{{ route('login') }}" class="vx-btn vx-btn-primary" style="justify-content: center; padding: 12px;">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
            <a href="{{ route('register') }}" class="vx-btn vx-btn-secondary" style="justify-content: center; padding: 12px;">
                <i class="bi bi-person-plus"></i> Crear Cuenta
            </a>
        </div>
        <p style="font-size: 11px; color: var(--vx-text-muted); margin-top: 24px;">
            Al registrarte tendrás acceso como cliente
        </p>
    </div>
</div>
@endauth

@endsection
