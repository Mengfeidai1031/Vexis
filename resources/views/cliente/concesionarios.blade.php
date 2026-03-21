@extends('layouts.app')
@section('title', 'Concesionarios - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-building" style="color:#34495E;"></i> Nuestros Centros</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($centros as $c)
    <div class="vx-card">
        <div style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--vx-primary),#2980b9);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;flex-shrink:0;"><i class="bi bi-geo-alt"></i></div>
                <div>
                    <h4 style="font-size:15px;font-weight:800;margin:0;">{{ $c->nombre }}</h4>
                    @if($c->empresa)<p style="margin:0;font-size:12px;color:var(--vx-text-muted);">{{ $c->empresa->nombre }}</p>@endif
                </div>
            </div>
            @if($c->direccion)<div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-pin-map" style="color:var(--vx-danger);margin-right:4px;"></i>{{ $c->direccion }}</div>@endif
            @if($c->municipio || $c->provincia)
            <div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-geo" style="color:var(--vx-info);margin-right:4px;"></i>{{ $c->municipio }}@if($c->provincia), {{ $c->provincia }}@endif</div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
