@extends('layouts.app')
@section('title', 'Campañas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Campañas y Promociones</h1><div class="vx-page-actions"><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div></div>

@if($campanias->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:20px;">
    @foreach($campanias as $c)
    <div class="vx-card cli-cmp-card" style="overflow:hidden;">
        @if($c->fotos->count() > 0)
        <div class="cli-cmp-carousel">
            <div class="cli-cmp-track" id="cliCmpTrack{{ $c->id }}">
                @foreach($c->fotos as $foto)
                <div class="cli-cmp-slide">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->ruta) }}" alt="{{ $c->nombre }}" class="cli-cmp-image">
                </div>
                @endforeach
            </div>
            @if($c->fotos->count() > 1)
            <button class="cli-cmp-arrow cli-cmp-prev" type="button" onclick="moveClienteCampaniaSlide({{ $c->id }}, -1)" aria-label="Foto anterior">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="cli-cmp-arrow cli-cmp-next" type="button" onclick="moveClienteCampaniaSlide({{ $c->id }}, 1)" aria-label="Siguiente foto">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div class="cli-cmp-dots" id="cliCmpDots{{ $c->id }}">
                @foreach($c->fotos as $foto)
                <button class="cli-cmp-dot {{ $loop->first ? 'active' : '' }}" type="button" onclick="goClienteCampaniaSlide({{ $c->id }}, {{ $loop->index }})"></button>
                @endforeach
            </div>
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
@push('styles')
<style>
.cli-cmp-card { transition: all 0.25s cubic-bezier(0.4,0,0.2,1); }
.cli-cmp-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
.cli-cmp-carousel { position: relative; overflow: hidden; height: 220px; background: var(--vx-gray-100); border-bottom: 1px solid var(--vx-border); }
.cli-cmp-track { display: flex; transition: transform 0.4s cubic-bezier(0.4,0,0.2,1); height: 100%; }
.cli-cmp-slide { min-width: 100%; height: 100%; }
.cli-cmp-image { width: 100%; height: 100%; object-fit: cover; }
.cli-cmp-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 50%; border: none; background: rgba(0,0,0,0.5); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; backdrop-filter: blur(4px); transition: all 0.2s; }
.cli-cmp-prev { left: 10px; }
.cli-cmp-next { right: 10px; }
.cli-cmp-arrow:hover { background: rgba(0,0,0,0.7); transform: translateY(-50%) scale(1.1); }
.cli-cmp-dots { position: absolute; left: 50%; bottom: 10px; transform: translateX(-50%); display: flex; gap: 6px; }
.cli-cmp-dot { width: 8px; height: 8px; border-radius: 50%; border: none; background: rgba(255,255,255,0.5); cursor: pointer; padding: 0; transition: all 0.2s; }
.cli-cmp-dot:hover { background: rgba(255,255,255,0.8); }
.cli-cmp-dot.active { width: 20px; border-radius: 4px; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
</style>
@endpush
@push('scripts')
<script>
const clienteCampaniaSlides = {};

function updateClienteCampaniaSlide(campaniaId) {
    const track = document.getElementById(`cliCmpTrack${campaniaId}`);
    if (!track) return;

    const index = clienteCampaniaSlides[campaniaId] ?? 0;
    track.style.transform = `translateX(-${index * 100}%)`;

    const dots = document.querySelectorAll(`#cliCmpDots${campaniaId} .cli-cmp-dot`);
    dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
}

function goClienteCampaniaSlide(campaniaId, index) {
    const total = document.querySelectorAll(`#cliCmpTrack${campaniaId} .cli-cmp-slide`).length;
    if (!total) return;

    clienteCampaniaSlides[campaniaId] = ((index % total) + total) % total;
    updateClienteCampaniaSlide(campaniaId);
}

function moveClienteCampaniaSlide(campaniaId, step) {
    const current = clienteCampaniaSlides[campaniaId] ?? 0;
    goClienteCampaniaSlide(campaniaId, current + step);
}
</script>
@endpush
@endsection
