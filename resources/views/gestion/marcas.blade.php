@extends('layouts.app')
@section('title', 'Marcas - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Marcas</h1>
    <a href="{{ route('gestion.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Gestión</a>
</div>
<p style="color:var(--vx-text-muted);margin-bottom:20px;">Marcas de vehículos gestionadas por Grupo ARI.</p>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
    @foreach($marcas as $marca)
    <div class="vx-card">
        <div class="vx-card-body" style="display:flex;align-items:center;gap:16px;">
            <div style="width:56px;height:56px;border-radius:12px;background:{{ $marca->color }}20;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-tags" style="font-size:24px;color:{{ $marca->color }};"></i>
            </div>
            <div style="flex:1;">
                <h3 style="font-size:18px;font-weight:700;margin:0;">{{ $marca->nombre }}</h3>
                <div style="font-size:12px;color:var(--vx-text-muted);margin-top:2px;">
                    <span class="vx-badge" style="background:{{ $marca->color }}20;color:{{ $marca->color }};">{{ $marca->slug }}</span>
                    @if($marca->activa)
                        <span class="vx-badge vx-badge-success">Activa</span>
                    @else
                        <span class="vx-badge vx-badge-gray">Inactiva</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
