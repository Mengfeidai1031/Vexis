@extends('layouts.app')
@section('title', 'Campañas - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Gestión de Campañas</h1>
    <div class="vx-page-actions">
        @can('crear campanias')
            <a href="{{ route('campanias.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Campaña</a>
        @endcan
    </div>
</div>
<form action="{{ route('campanias.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar campaña..." value="{{ request('search') }}" style="flex:1;">
    <select name="marca_id" class="vx-select" style="width:auto;">
        <option value="">Todas las marcas</option>
        @foreach($marcas as $marca)
            <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
        @endforeach
    </select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','marca_id']))<a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
@if($campanias->count() > 0)
    @foreach($campanias as $campania)
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h4 style="margin:0;">{{ $campania->nombre }}</h4>
                <div style="font-size:12px;color:var(--vx-text-muted);margin-top:2px;">
                    <span class="vx-badge" style="background:{{ $campania->marca->color }}20;color:{{ $campania->marca->color }};">{{ $campania->marca->nombre }}</span>
                    @if($campania->fecha_inicio) {{ $campania->fecha_inicio->format('d/m/Y') }} @endif
                    @if($campania->fecha_fin) — {{ $campania->fecha_fin->format('d/m/Y') }} @endif
                    @if($campania->activa)<span class="vx-badge vx-badge-success">Activa</span>@else<span class="vx-badge vx-badge-gray">Inactiva</span>@endif
                    · {{ $campania->fotos->count() }} foto(s)
                </div>
            </div>
            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('campanias.show', $campania) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                @can('editar campanias')<a href="{{ route('campanias.edit', $campania) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar campanias')
                <form action="{{ route('campanias.destroy', $campania) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar campaña y todas sus fotos?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                </form>
                @endcan
            </div></div>
        </div>
        @if($campania->fotos->count() > 0)
        <div class="vx-card-body" style="padding:12px;">
            <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;">
                @foreach($campania->fotos as $foto)
                <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="height:100px;width:auto;border-radius:6px;object-fit:cover;flex-shrink:0;">
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endforeach
    <div style="padding:8px 0;">{{ $campanias->links('vendor.pagination.vexis') }}</div>
@else
    <div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-megaphone"></i><p>No se encontraron campañas.</p></div></div></div>
@endif
@endsection
