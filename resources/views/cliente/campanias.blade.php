@extends('layouts.app')
@section('title', 'Campañas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-megaphone" style="color:var(--vx-warning);"></i> Campañas Activas</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($campanias->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:16px;">
    @foreach($campanias as $ci => $c)
    <div class="vx-card" style="overflow:hidden;">
        @if($c->fotos->count() > 0)
        <div class="camp-carousel" data-id="cl{{ $ci }}">
            <div class="camp-track" id="campTrackCl{{ $ci }}" style="display:flex;transition:transform 0.3s ease;">
                @foreach($c->fotos as $foto)
                <div style="min-width:100%;height:200px;display:flex;align-items:center;justify-content:center;background:var(--vx-bg);">
                    <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="max-height:200px;max-width:100%;object-fit:contain;">
                </div>
                @endforeach
            </div>
            @if($c->fotos->count() > 1)
            <button class="camp-arr camp-prev" onclick="moveCamp('cl{{ $ci }}',-1)"><i class="bi bi-chevron-left"></i></button>
            <button class="camp-arr camp-next" onclick="moveCamp('cl{{ $ci }}',1)"><i class="bi bi-chevron-right"></i></button>
            <div class="camp-counter" id="campCounterCl{{ $ci }}">1 / {{ $c->fotos->count() }}</div>
            @endif
        </div>
        @else
        <div style="height:200px;display:flex;align-items:center;justify-content:center;background:var(--vx-bg);"><i class="bi bi-image" style="font-size:40px;color:var(--vx-text-muted);"></i></div>
        @endif
        <div style="padding:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <h3 style="font-size:16px;font-weight:800;margin:0;">{{ $c->nombre }}</h3>
                @if($c->marca)<span class="vx-badge" style="background:{{ $c->marca->color }}20;color:{{ $c->marca->color }};font-size:10px;">{{ $c->marca->nombre }}</span>@endif
            </div>
            @if($c->descripcion)<p style="font-size:13px;color:var(--vx-text-muted);line-height:1.5;margin:0 0 8px;">{{ Str::limit($c->descripcion, 150) }}</p>@endif
            <div style="font-size:11px;color:var(--vx-text-muted);">
                <i class="bi bi-calendar-range"></i>
                {{ $c->fecha_inicio?->format('d/m/Y') ?? '—' }} — {{ $c->fecha_fin?->format('d/m/Y') ?? '—' }}
                @if($c->fecha_fin && $c->fecha_fin->isFuture())<span class="vx-badge vx-badge-success" style="font-size:9px;margin-left:4px;">Activa</span>@endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-megaphone"></i><p>No hay campañas activas en este momento.</p></div></div></div>
@endif

@push('styles')
<style>
.camp-carousel { position:relative; overflow:hidden; }
.camp-arr { position:absolute; top:50%; transform:translateY(-50%); width:32px; height:32px; border-radius:50%; border:1px solid rgba(255,255,255,0.3); background:rgba(0,0,0,0.4); color:white; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:5; transition:all 0.15s; }
.camp-arr:hover { background:var(--vx-primary); border-color:var(--vx-primary); }
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
    const track = document.getElementById('campTrackCl' + id.replace('cl',''));
    if (!track) return;
    const total = track.children.length;
    campSlides[id].current += dir;
    if (campSlides[id].current < 0) campSlides[id].current = total - 1;
    if (campSlides[id].current >= total) campSlides[id].current = 0;
    track.style.transform = `translateX(-${campSlides[id].current * 100}%)`;
    const counter = document.getElementById('campCounterCl' + id.replace('cl',''));
    if (counter) counter.textContent = (campSlides[id].current + 1) + ' / ' + total;
}
</script>
@endpush
@endsection
