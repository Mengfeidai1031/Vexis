@extends('layouts.app')
@section('title', 'Noticias - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-newspaper" style="color:var(--vx-primary);"></i> Noticias</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

@if($noticias->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:20px;">
    @foreach($noticias as $noticia)
    <div class="vx-card" style="overflow:hidden;display:flex;flex-direction:column;">
        @if($noticia->imagen_url)
        <div style="height:180px;overflow:hidden;background:var(--vx-bg);">
            <img src="{{ $noticia->imagen_url }}" alt="{{ $noticia->titulo }}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        @endif
        <div style="padding:20px;flex:1;display:flex;flex-direction:column;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;gap:8px;">
                <h3 style="font-size:16px;font-weight:800;margin:0;flex:1;">{{ $noticia->titulo }}</h3>
                @if($noticia->destacada)<span class="vx-badge vx-badge-warning" style="font-size:10px;flex-shrink:0;"><i class="bi bi-star-fill"></i> Destacada</span>@endif
            </div>
            <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                <span class="vx-badge" style="background:rgba(51,170,221,0.1);color:var(--vx-primary);font-size:10px;">{{ \App\Models\Noticia::$categorias[$noticia->categoria] ?? $noticia->categoria }}</span>
                <span style="font-size:11px;color:var(--vx-text-muted);"><i class="bi bi-calendar3"></i> {{ $noticia->fecha_publicacion?->format('d/m/Y') ?? $noticia->created_at->format('d/m/Y') }}</span>
            </div>
            <p style="font-size:13px;color:var(--vx-text-muted);margin:0 0 12px;line-height:1.6;flex:1;">{{ Str::limit(strip_tags($noticia->contenido), 200) }}</p>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:11px;color:var(--vx-text-muted);margin-top:auto;">
                <span><i class="bi bi-person"></i> {{ $noticia->autor?->nombre_completo ?? 'Grupo DAI' }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-newspaper"></i><p>No hay noticias publicadas en este momento.</p></div></div></div>
@endif
@endsection
