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
    @foreach($campanias as $ci => $campania)
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-header" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h4 style="margin:0;">{{ $campania->nombre }}</h4>
                <div style="font-size:12px;color:var(--vx-text-muted);margin-top:2px;">
                    @if($campania->marca)<span class="vx-badge" style="background:{{ $campania->marca->color }}20;color:{{ $campania->marca->color }};">{{ $campania->marca->nombre }}</span>@endif
                    @if($campania->fecha_inicio) {{ $campania->fecha_inicio->format('d/m/Y') }} @endif
                    @if($campania->fecha_fin) — {{ $campania->fecha_fin->format('d/m/Y') }} @endif
                    @if($campania->activa)<span class="vx-badge vx-badge-success">Activa</span>@else<span class="vx-badge vx-badge-gray">Inactiva</span>@endif
                </div>
            </div>
            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('campanias.show', $campania) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                @can('editar campanias')<a href="{{ route('campanias.edit', $campania) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar campanias')
                <form action="{{ route('campanias.destroy', $campania) }}" method="POST" onsubmit="return confirm('¿Eliminar campaña y todas sus fotos?');">@csrf @method('DELETE')
                    <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                </form>
                @endcan
            </div></div>
        </div>
        @if($campania->fotos->count() > 0)
        <div class="vx-card-body" style="padding:0;">
            <div class="camp-carousel" data-id="{{ $ci }}">
                <div class="camp-track" id="campTrack{{ $ci }}" style="display:flex;transition:transform 0.3s ease;">
                    @foreach($campania->fotos as $foto)
                    <div style="min-width:100%;display:flex;align-items:center;justify-content:center;background:var(--vx-bg);padding:12px;">
                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="max-height:240px;max-width:100%;object-fit:contain;border-radius:8px;">
                    </div>
                    @endforeach
                </div>
                @if($campania->fotos->count() > 1)
                <button class="camp-arr camp-prev" onclick="moveCamp({{ $ci }},-1)"><i class="bi bi-chevron-left"></i></button>
                <button class="camp-arr camp-next" onclick="moveCamp({{ $ci }},1)"><i class="bi bi-chevron-right"></i></button>
                <div class="camp-counter" id="campCounter{{ $ci }}">1 / {{ $campania->fotos->count() }}</div>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endforeach
    <div style="padding:8px 0;">{{ $campanias->links('vendor.pagination.vexis') }}</div>
@else
    <div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-megaphone"></i><p>No se encontraron campañas.</p></div></div></div>
@endif

@push('styles')
<style>
.camp-carousel { position:relative; overflow:hidden; }
.camp-arr { position:absolute; top:50%; transform:translateY(-50%); width:32px; height:32px; border-radius:50%; border:1px solid var(--vx-border); background:var(--vx-surface); color:var(--vx-text); display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:5; transition:all 0.15s; box-shadow:var(--vx-shadow-sm); opacity:0.8; }
.camp-arr:hover { background:var(--vx-primary); color:white; border-color:var(--vx-primary); opacity:1; }
.camp-prev { left:8px; }
.camp-next { right:8px; }
.camp-counter { position:absolute; bottom:8px; right:12px; font-size:11px; background:rgba(0,0,0,0.5); color:white; padding:2px 8px; border-radius:10px; }
</style>
@endpush

@push('scripts')
<script>
const campSlides = {};
function moveCamp(id, dir) {
    if (!campSlides[id]) campSlides[id] = { current: 0 };
    const track = document.getElementById('campTrack' + id);
    const total = track.children.length;
    campSlides[id].current += dir;
    if (campSlides[id].current < 0) campSlides[id].current = total - 1;
    if (campSlides[id].current >= total) campSlides[id].current = 0;
    track.style.transform = `translateX(-${campSlides[id].current * 100}%)`;
    const counter = document.getElementById('campCounter' + id);
    if (counter) counter.textContent = (campSlides[id].current + 1) + ' / ' + total;
}
</script>
@endpush
@endsection
