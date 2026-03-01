@extends('layouts.app')
@section('title', 'Campañas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-megaphone" style="color:var(--vx-warning);"></i> Campañas y Promociones</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($campanias->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:20px;">
    @foreach($campanias as $c)
    <div class="vx-card" style="overflow:hidden;">
        @if($c->fotos->count() > 0)
        <div style="position:relative;height:200px;overflow:hidden;">
            <img src="{{ asset('storage/' . $c->fotos->first()->ruta) }}" alt="{{ $c->nombre }}" style="width:100%;height:100%;object-fit:cover;">
            @if($c->fotos->count() > 1)
            <span style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.6);color:white;padding:2px 8px;border-radius:12px;font-size:11px;"><i class="bi bi-images"></i> {{ $c->fotos->count() }}</span>
            @endif
        </div>
        @endif
        <div style="padding:16px 20px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                <h3 style="font-size:16px;font-weight:800;margin:0;">{{ $c->nombre }}</h3>
                @if($c->marca)<span class="vx-badge" style="background:{{ $c->marca->color }}20;color:{{ $c->marca->color }};font-size:10px;">{{ $c->marca->nombre }}</span>@endif
            </div>
            @if($c->descripcion)<p style="font-size:13px;color:var(--vx-text-muted);margin:0 0 12px;line-height:1.5;">{{ Str::limit($c->descripcion, 150) }}</p>@endif
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:11px;color:var(--vx-text-muted);">
                <span><i class="bi bi-calendar"></i> {{ $c->fecha_inicio?->format('d/m/Y') ?? '' }} — {{ $c->fecha_fin?->format('d/m/Y') ?? '' }}</span>
                @if($c->fecha_fin && $c->fecha_fin->isFuture())<span class="vx-badge vx-badge-success">Activa</span>@endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-megaphone"></i><p>No hay campañas activas en este momento.</p></div></div></div>
@endif
@endsection
