@extends('layouts.app')

@section('title', 'Página no encontrada - VEXIS')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--vx-navbar-height) - 120px);">
    <div style="text-align: center; max-width: 440px;">
        <div style="font-size: 64px; color: var(--vx-primary); margin-bottom: 16px;">
            <i class="bi bi-compass"></i>
        </div>
        <div style="font-size: 56px; font-weight: 900; color: var(--vx-primary); line-height: 1; margin-bottom: 4px;">404</div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--vx-text); margin-bottom: 8px;">Página no encontrada</h1>
        <p style="font-size: 14px; color: var(--vx-text-secondary); margin-bottom: 24px; line-height: 1.6;">
            La URL solicitada no existe o ha sido movida. Verifica el enlace o vuelve al dashboard.
        </p>
        <div style="display: flex; gap: 8px; justify-content: center;">
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="vx-btn vx-btn-primary">
                <i class="bi bi-house"></i> Inicio
            </a>
            <a href="javascript:history.back()" class="vx-btn vx-btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
