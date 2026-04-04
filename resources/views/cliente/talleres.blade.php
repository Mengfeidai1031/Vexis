@extends('layouts.app')
@section('title', 'Talleres - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-tools" style="color:#e67e22;"></i> Nuestros Talleres</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($talleres->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($talleres as $taller)
    <div class="vx-card">
        <div style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#e67e22,#d35400);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;flex-shrink:0;"><i class="bi bi-tools"></i></div>
                <div style="flex:1;min-width:0;">
                    <h4 style="font-size:15px;font-weight:800;margin:0;">{{ $taller->nombre }}</h4>
                    @if($taller->empresa)
                    <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">{{ $taller->empresa->nombre }}</p>
                    @endif
                </div>
                @if($taller->marca)
                <span class="vx-badge" style="background:{{ $taller->marca->color ?? 'var(--vx-primary)' }}20;color:{{ $taller->marca->color ?? 'var(--vx-primary)' }};font-size:10px;flex-shrink:0;">{{ $taller->marca->nombre }}</span>
                @endif
            </div>
            @if($taller->domicilio)
            <div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-geo-alt" style="color:var(--vx-danger);margin-right:4px;"></i>{{ $taller->domicilio }}</div>
            @endif
            @if($taller->localidad || $taller->isla)
            <div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-signpost-2" style="color:var(--vx-info);margin-right:4px;"></i>{{ $taller->localidad ?? '' }}@if($taller->localidad && $taller->isla), @endif{{ $taller->isla ?? '' }}</div>
            @endif
            @if($taller->telefono)
            <div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-telephone" style="color:var(--vx-success);margin-right:4px;"></i>{{ $taller->telefono }}</div>
            @endif
            @if($taller->codigo_postal)
            <div style="font-size:12px;margin-bottom:0;"><i class="bi bi-envelope" style="color:var(--vx-warning);margin-right:4px;"></i>CP {{ $taller->codigo_postal }}</div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-tools"></i><p>No hay talleres disponibles en este momento.</p></div></div></div>
@endif
@endsection
