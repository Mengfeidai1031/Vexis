@extends('layouts.app')
@section('title', 'Noticias - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-newspaper" style="color:var(--vx-primary);"></i> Noticias</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($noticias->count() > 0)
<div class="news-carousel-shell">
    <div class="news-controls-row">
        <button class="news-arrow news-prev" onclick="moveSlide(-1)" aria-label="Noticia anterior"><i class="bi bi-chevron-left"></i></button>
        <div style="display:flex;justify-content:center;gap:6px;" id="newsDots">
            @foreach($noticias as $i => $n)
            <button class="news-dot {{ $i === 0 ? 'active' : '' }}" onclick="goSlide({{ $i }})" aria-label="Ir a noticia {{ $i + 1 }}"></button>
            @endforeach
        </div>
        <button class="news-arrow news-next" onclick="moveSlide(1)" aria-label="Noticia siguiente"><i class="bi bi-chevron-right"></i></button>
    </div>

    <div class="vx-card news-card">
        <div id="newsCarousel" style="overflow:hidden;">
            <div id="newsTrack" style="display:flex;transition:transform 0.4s ease;">
                @foreach($noticias as $i => $noticia)
                <div class="news-slide" style="min-width:100%;padding:28px 32px;box-sizing:border-box;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                        <span class="vx-badge vx-badge-info">{{ \App\Models\Noticia::$categorias[$noticia->categoria] ?? $noticia->categoria }}</span>
                        @if($noticia->destacada)<span class="vx-badge vx-badge-warning" style="font-size:10px;"><i class="bi bi-star-fill"></i> Destacada</span>@endif
                    </div>
                    <h3 style="font-size:20px;font-weight:800;margin:0 0 8px;">{{ $noticia->titulo }}</h3>
                    <p style="font-size:13px;color:var(--vx-text-muted);line-height:1.6;margin:0 0 12px;">{{ Str::limit($noticia->contenido, 300) }}</p>
                    <div style="display:flex;align-items:center;gap:16px;font-size:12px;color:var(--vx-text-muted);">
                        <span><i class="bi bi-person"></i> {{ $noticia->autor?->nombre_completo ?? 'Grupo DAI' }}</span>
                        <span><i class="bi bi-calendar"></i> {{ $noticia->fecha_publicacion?->format('d/m/Y') ?? $noticia->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-newspaper"></i><p>No hay noticias publicadas en este momento.</p></div></div></div>
@endif

@push('styles')
<style>
.news-carousel-shell { margin-bottom: 20px; }
.news-controls-row { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:10px; }
.news-card { overflow:hidden; }
.news-arrow { width:36px; height:36px; border-radius:50%; border:1px solid var(--vx-border); background:var(--vx-surface); color:var(--vx-text); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.15s; box-shadow:var(--vx-shadow-sm); flex-shrink:0; }
.news-arrow:hover { background:var(--vx-primary); color:white; border-color:var(--vx-primary); }
.news-dot { width:8px; height:8px; border-radius:50%; border:none; background:var(--vx-gray-300); cursor:pointer; transition:all 0.2s; padding:0; }
.news-dot.active { background:var(--vx-primary); width:20px; border-radius:4px; }
</style>
@endpush

@push('scripts')
<script>
let currentSlide = 0;
const total = {{ $noticias->count() }};
let autoplay;

function goSlide(n) {
    currentSlide = n;
    if (currentSlide < 0) currentSlide = total - 1;
    if (currentSlide >= total) currentSlide = 0;
    document.getElementById('newsTrack').style.transform = `translateX(-${currentSlide * 100}%)`;
    document.querySelectorAll('.news-dot').forEach((d, i) => d.classList.toggle('active', i === currentSlide));
    resetAutoplay();
}
function moveSlide(dir) { goSlide(currentSlide + dir); }
function resetAutoplay() { clearInterval(autoplay); autoplay = setInterval(() => moveSlide(1), 6000); }
resetAutoplay();
</script>
@endpush
@endsection
