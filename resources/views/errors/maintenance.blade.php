@extends('layouts.app')
@section('title', 'Mantenimiento - VEXIS')
@section('content')
<div style="min-height:calc(100vh - var(--vx-navbar-height) - 60px);display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="max-width:520px;text-align:center;">
        <i class="bi bi-tools" style="font-size:72px;color:var(--vx-warning);"></i>
        <h1 style="font-size:28px;margin:16px 0 8px;color:var(--vx-text);">Modo Mantenimiento</h1>
        <p style="color:var(--vx-text-muted);font-size:14px;line-height:1.6;">VEXIS está en mantenimiento programado. El acceso está limitado a administradores. Vuelve a intentarlo en unos minutos.</p>
        <a href="{{ route('login') }}" class="vx-btn vx-btn-secondary" style="margin-top:20px;"><i class="bi bi-arrow-left"></i> Volver al login</a>
    </div>
</div>
@endsection
