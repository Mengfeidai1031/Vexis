@extends('layouts.app')
@section('title', $campania->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $campania->nombre }}</h1>
    <div class="vx-page-actions">
        @can('editar campanias')<a href="{{ route('campanias.edit', $campania) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('campanias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:900px;">
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-body">
            <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
                <span class="vx-badge" style="background:{{ $campania->marca->color }}20;color:{{ $campania->marca->color }};">{{ $campania->marca->nombre }}</span>
                @if($campania->activa)<span class="vx-badge vx-badge-success">Activa</span>@else<span class="vx-badge vx-badge-gray">Inactiva</span>@endif
            </div>
            @if($campania->descripcion)<p style="color:var(--vx-text-secondary);font-size:14px;">{{ $campania->descripcion }}</p>@endif
            <div style="font-size:13px;color:var(--vx-text-muted);">
                @if($campania->fecha_inicio)<i class="bi bi-calendar3"></i> {{ $campania->fecha_inicio->format('d/m/Y') }} @endif
                @if($campania->fecha_fin) — {{ $campania->fecha_fin->format('d/m/Y') }} @endif
            </div>
        </div>
    </div>

    @if($campania->fotos->count() > 0)
    <h3 style="font-size:15px;font-weight:700;margin-bottom:12px;">Galería ({{ $campania->fotos->count() }} fotos)</h3>
    {{-- Carrusel --}}
    <div class="vx-card" style="margin-bottom:16px;">
        <div class="vx-card-body" style="padding:0;position:relative;overflow:hidden;">
            <div id="carrusel" style="display:flex;transition:transform 0.4s ease;">
                @foreach($campania->fotos as $foto)
                <div style="min-width:100%;display:flex;align-items:center;justify-content:center;background:var(--vx-gray-100);padding:16px;">
                    <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="max-height:400px;max-width:100%;object-fit:contain;border-radius:8px;">
                </div>
                @endforeach
            </div>
            @if($campania->fotos->count() > 1)
            <button onclick="moveSlide(-1)" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);color:white;border:none;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:16px;"><i class="bi bi-chevron-left"></i></button>
            <button onclick="moveSlide(1)" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);color:white;border:none;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:16px;"><i class="bi bi-chevron-right"></i></button>
            @endif
        </div>
    </div>

    {{-- Thumbnails --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;">
        @foreach($campania->fotos as $foto)
        <div style="position:relative;">
            <img src="{{ asset('storage/' . $foto->ruta) }}" alt="{{ $foto->nombre_original }}" style="width:100%;height:80px;object-fit:cover;border-radius:6px;cursor:pointer;" onclick="goToSlide({{ $loop->index }})">
        </div>
        @endforeach
    </div>
    @else
    <div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-image"></i><p>Esta campaña no tiene fotos.</p></div></div></div>
    @endif
</div>
@push('scripts')
<script>
let currentSlide = 0;
const totalSlides = {{ $campania->fotos->count() }};
function goToSlide(n) { currentSlide = n; document.getElementById('carrusel').style.transform = `translateX(-${n * 100}%)`; }
function moveSlide(d) { currentSlide = (currentSlide + d + totalSlides) % totalSlides; goToSlide(currentSlide); }
</script>
@endpush
@endsection
