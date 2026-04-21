@extends('layouts.app')

@section('title', 'Sesión expirada - VEXIS')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--vx-navbar-height) - 120px);">
    <div style="text-align: center; max-width: 440px;">
        <div style="font-size: 64px; color: var(--vx-warning); margin-bottom: 16px;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div style="font-size: 56px; font-weight: 900; color: var(--vx-warning); line-height: 1; margin-bottom: 4px;">419</div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--vx-text); margin-bottom: 8px;">Sesión expirada</h1>
        <p style="font-size: 14px; color: var(--vx-text-secondary); margin-bottom: 24px; line-height: 1.6;">
            El token de sesión ha caducado por inactividad o seguridad. Vuelve a iniciar sesión para continuar.
        </p>
        <div style="display: flex; gap: 8px; justify-content: center;">
            <a href="{{ route('login') }}" class="vx-btn vx-btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
            <a href="javascript:history.back()" class="vx-btn vx-btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
