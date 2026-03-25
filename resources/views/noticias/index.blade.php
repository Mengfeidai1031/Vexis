@extends('layouts.app')
@section('title', 'Noticias - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Noticias</h1>
    <div class="vx-page-actions">
        @can('crear noticias')
            <a href="{{ route('noticias.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Noticia</a>
        @endcan
    </div>
</div>

{{-- Carrusel de noticias --}}
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
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                            <span class="vx-badge vx-badge-info">{{ \App\Models\Noticia::$categorias[$noticia->categoria] ?? $noticia->categoria }}</span>
                            @if($noticia->destacada)<span class="vx-badge vx-badge-warning" style="font-size:10px;">Destacada</span>@endif
                            @if($noticia->publicada)<span class="vx-badge vx-badge-success">Publicada</span>@else<span class="vx-badge vx-badge-gray">Borrador</span>@endif
                        </div>
                        <h3 style="font-size:20px;font-weight:800;margin:0 0 8px;">{{ $noticia->titulo }}</h3>
                        <p style="font-size:13px;color:var(--vx-text-muted);line-height:1.6;margin:0 0 12px;">{{ Str::limit($noticia->contenido, 200) }}</p>
                        <div style="display:flex;align-items:center;gap:16px;font-size:12px;color:var(--vx-text-muted);">
                            <span><i class="bi bi-person"></i> {{ $noticia->autor->nombre ?? '—' }}</span>
                            <span><i class="bi bi-calendar"></i> {{ $noticia->fecha_publicacion->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="news-actions-wrap">
        @foreach($noticias as $i => $noticia)
        <div class="news-actions-slide {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}">
            <a href="{{ route('noticias.show', $noticia) }}" class="vx-btn vx-btn-info vx-btn-sm"><i class="bi bi-eye"></i> Ver</a>
            @can('editar noticias')<a href="{{ route('noticias.edit', $noticia) }}" class="vx-btn vx-btn-warning vx-btn-sm"><i class="bi bi-pencil"></i> Editar</a>@endcan
            @can('eliminar noticias')
            <form action="{{ route('noticias.destroy', $noticia) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                @csrf @method('DELETE')
                <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm"><i class="bi bi-trash"></i> Eliminar</button>
            </form>
            @endcan
        </div>
        @endforeach
    </div>
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-newspaper"></i><p>No se encontraron noticias.</p></div></div></div>
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
.news-actions-wrap { display:flex; justify-content:center; margin-top:10px; min-height:34px; }
.news-actions-slide { display:none; align-items:center; gap:8px; flex-wrap:wrap; }
.news-actions-slide.active { display:flex; }
.news-actions-slide form { margin:0; }
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
    document.querySelectorAll('.news-actions-slide').forEach((a, i) => a.classList.toggle('active', i === currentSlide));
    resetAutoplay();
}
function moveSlide(dir) { goSlide(currentSlide + dir); }
function resetAutoplay() { clearInterval(autoplay); autoplay = setInterval(() => moveSlide(1), 6000); }
resetAutoplay();
</script>
@endpush
@endsection
