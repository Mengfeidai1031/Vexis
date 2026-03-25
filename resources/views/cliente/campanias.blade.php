@extends('layouts.app')
@section('title', 'Campañas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-megaphone" style="color:var(--vx-warning);"></i> Campañas y Promociones</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($campanias->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:20px;">
    @foreach($campanias as $c)
    <div class="vx-card" style="overflow:hidden;">
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
.cli-cmp-carousel { position: relative; overflow: hidden; height: 210px; background: var(--vx-gray-100); }
.cli-cmp-track { display: flex; transition: transform 0.35s ease; height: 100%; }
.cli-cmp-slide { min-width: 100%; height: 100%; }
.cli-cmp-image { width: 100%; height: 100%; object-fit: cover; }
.cli-cmp-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.7); background: rgba(0,0,0,0.45); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 2; }
.cli-cmp-prev { left: 10px; }
.cli-cmp-next { right: 10px; }
.cli-cmp-arrow:hover { background: rgba(0,0,0,0.62); }
.cli-cmp-dots { position: absolute; left: 50%; bottom: 8px; transform: translateX(-50%); display: flex; gap: 6px; }
.cli-cmp-dot { width: 7px; height: 7px; border-radius: 50%; border: none; background: rgba(255,255,255,0.55); cursor: pointer; padding: 0; }
.cli-cmp-dot.active { width: 18px; border-radius: 4px; background: #fff; }
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
