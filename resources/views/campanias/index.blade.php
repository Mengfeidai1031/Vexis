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
            <div class="cmp-carousel" data-cmp-carousel="{{ $campania->id }}">
                <div class="cmp-track" id="cmpTrack{{ $campania->id }}">
                    @foreach($campania->fotos as $foto)
                    <div class="cmp-slide">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->ruta) }}" alt="{{ $foto->nombre_original }}" class="cmp-image">
                    </div>
                    @endforeach
                </div>
                @if($campania->fotos->count() > 1)
                <button class="cmp-arrow cmp-prev" type="button" onclick="moveCampaniaSlide({{ $campania->id }}, -1)" aria-label="Foto anterior">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="cmp-arrow cmp-next" type="button" onclick="moveCampaniaSlide({{ $campania->id }}, 1)" aria-label="Siguiente foto">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <div class="cmp-dots" id="cmpDots{{ $campania->id }}">
                    @foreach($campania->fotos as $foto)
                    <button class="cmp-dot {{ $loop->first ? 'active' : '' }}" type="button" onclick="goCampaniaSlide({{ $campania->id }}, {{ $loop->index }})"></button>
                    @endforeach
                </div>
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
.cmp-carousel { position: relative; overflow: hidden; border-radius: 8px; background: var(--vx-gray-100); }
.cmp-track { display: flex; transition: transform 0.35s ease; }
.cmp-slide { min-width: 100%; height: 180px; display: flex; align-items: center; justify-content: center; }
.cmp-image { width: 100%; height: 100%; object-fit: cover; }
.cmp-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.7); background: rgba(0,0,0,0.45); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; }
.cmp-prev { left: 10px; }
.cmp-next { right: 10px; }
.cmp-arrow:hover { background: rgba(0,0,0,0.62); }
.cmp-dots { position: absolute; left: 50%; bottom: 8px; transform: translateX(-50%); display: flex; gap: 6px; }
.cmp-dot { width: 7px; height: 7px; border-radius: 50%; border: none; background: rgba(255,255,255,0.55); cursor: pointer; padding: 0; }
.cmp-dot.active { width: 18px; border-radius: 4px; background: #fff; }
</style>
@endpush
@push('scripts')
<script>
const campaniaSlides = {};

function updateCampaniaSlide(campaniaId) {
    const track = document.getElementById(`cmpTrack${campaniaId}`);
    if (!track) return;

    const index = campaniaSlides[campaniaId] ?? 0;
    track.style.transform = `translateX(-${index * 100}%)`;

    const dots = document.querySelectorAll(`#cmpDots${campaniaId} .cmp-dot`);
    dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
}

function goCampaniaSlide(campaniaId, index) {
    const total = document.querySelectorAll(`#cmpTrack${campaniaId} .cmp-slide`).length;
    if (!total) return;

    campaniaSlides[campaniaId] = ((index % total) + total) % total;
    updateCampaniaSlide(campaniaId);
}

function moveCampaniaSlide(campaniaId, step) {
    const current = campaniaSlides[campaniaId] ?? 0;
    goCampaniaSlide(campaniaId, current + step);
}
</script>
@endpush
@endsection
