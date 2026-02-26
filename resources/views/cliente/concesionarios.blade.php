@extends('layouts.app')
@section('title', 'Concesionarios - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-building" style="color:#34495E;"></i> Nuestros Concesionarios</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    @foreach($empresas as $e)
    <div class="vx-card">
        <div style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--vx-primary),#2980b9);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;flex-shrink:0;"><i class="bi bi-building"></i></div>
                <div>
                    <h4 style="font-size:15px;font-weight:800;margin:0;">{{ $e->nombre }}</h4>
                    @if($e->localidad)<p style="margin:0;font-size:12px;color:var(--vx-text-muted);">{{ $e->localidad }}@if($e->isla), {{ $e->isla }}@endif</p>@endif
                </div>
            </div>
            @if($e->domicilio)<div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-geo-alt" style="color:var(--vx-danger);margin-right:4px;"></i>{{ $e->domicilio }}</div>@endif
            @if($e->telefono)<div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-telephone" style="color:var(--vx-success);margin-right:4px;"></i>{{ $e->telefono }}</div>@endif
            @if($e->email)<div style="font-size:12px;margin-bottom:6px;"><i class="bi bi-envelope" style="color:var(--vx-info);margin-right:4px;"></i>{{ $e->email }}</div>@endif
            @if($e->marcas_list && $e->marcas_list->count() > 0)
            <div style="margin-top:12px;display:flex;gap:6px;flex-wrap:wrap;">
                @foreach($e->marcas_list as $m)
                <span class="vx-badge" style="background:{{ $m->color }}20;color:{{ $m->color }};">{{ $m->nombre }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
