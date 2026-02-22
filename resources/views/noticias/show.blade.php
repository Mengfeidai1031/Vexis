@extends('layouts.app')
@section('title', $noticia->titulo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $noticia->titulo }}</h1>
    <div class="vx-page-actions">
        @can('editar noticias')<a href="{{ route('noticias.edit', $noticia) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        <a href="{{ route('noticias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
<div style="max-width:800px;">
    <div class="vx-card">
        <div class="vx-card-body">
            <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
                <span class="vx-badge vx-badge-info">{{ \App\Models\Noticia::$categorias[$noticia->categoria] ?? $noticia->categoria }}</span>
                @if($noticia->destacada)<span class="vx-badge vx-badge-warning">⭐ Destacada</span>@endif
                @if($noticia->publicada)<span class="vx-badge vx-badge-success">Publicada</span>@else<span class="vx-badge vx-badge-gray">Borrador</span>@endif
            </div>
            <div style="font-size:13px;color:var(--vx-text-muted);margin-bottom:20px;">
                <i class="bi bi-person"></i> {{ $noticia->autor->nombre_completo ?? '—' }} · <i class="bi bi-calendar3"></i> {{ $noticia->fecha_publicacion->format('d/m/Y H:i') }}
            </div>
            <div style="font-size:14px;line-height:1.8;color:var(--vx-text);">
                {!! nl2br(e($noticia->contenido)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
