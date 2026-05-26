@extends('layouts.app')

@section('title', 'Servicio no disponible - VEXIS')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--vx-navbar-height) - 120px);">
    <div style="text-align: center; max-width: 460px;">
        <div style="font-size: 64px; color: var(--vx-warning); margin-bottom: 16px;">
            <i class="bi bi-tools"></i>
        </div>
        <div style="font-size: 56px; font-weight: 900; color: var(--vx-warning); line-height: 1; margin-bottom: 4px;">503</div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--vx-text); margin-bottom: 8px;">Servicio en mantenimiento</h1>
        <p style="font-size: 14px; color: var(--vx-text-secondary); margin-bottom: 24px; line-height: 1.6;">
            VEXIS está temporalmente fuera de servicio por tareas de mantenimiento. Estará disponible en breve.
        </p>
    </div>
</div>
@endsection
