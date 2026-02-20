@extends('layouts.app')

@section('title', 'Acceso Denegado - VEXIS')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--vx-navbar-height) - 120px);">
    <div style="text-align: center; max-width: 440px;">
        <div style="font-size: 64px; color: var(--vx-danger); margin-bottom: 16px;">
            <i class="bi bi-shield-x"></i>
        </div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--vx-text); margin-bottom: 8px;">Acceso Denegado</h1>
        <p style="font-size: 14px; color: var(--vx-text-secondary); margin-bottom: 24px; line-height: 1.6;">
            No tienes permisos para acceder a esta página. Si crees que esto es un error, contacta con tu administrador del sistema.
        </p>
        <div style="display: flex; gap: 8px; justify-content: center;">
            <a href="{{ route('dashboard') }}" class="vx-btn vx-btn-primary">
                <i class="bi bi-grid-1x2"></i> Ir al Dashboard
            </a>
            <a href="javascript:history.back()" class="vx-btn vx-btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
