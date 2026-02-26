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
<form action="{{ route('noticias.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por título o contenido..." value="{{ request('search') }}" style="flex:1;">
    <select name="categoria" class="vx-select" style="width:auto;">
        <option value="">Todas las categorías</option>
        @foreach(\App\Models\Noticia::$categorias as $k => $v)
            <option value="{{ $k }}" {{ request('categoria') == $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','categoria']))<a href="{{ route('noticias.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($noticias->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><th>Título</th><th>Categoría</th><th>Autor</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($noticias as $noticia)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ Str::limit($noticia->titulo, 50) }}</div>
                            @if($noticia->destacada)<span class="vx-badge vx-badge-warning" style="font-size:10px;">⭐ Destacada</span>@endif
                        </td>
                        <td><span class="vx-badge vx-badge-info">{{ \App\Models\Noticia::$categorias[$noticia->categoria] ?? $noticia->categoria }}</span></td>
                        <td style="font-size:12px;">{{ $noticia->autor->nombre ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $noticia->fecha_publicacion->format('d/m/Y') }}</td>
                        <td>
                            @if($noticia->publicada)<span class="vx-badge vx-badge-success">Publicada</span>
                            @else<span class="vx-badge vx-badge-gray">Borrador</span>@endif
                        </td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                <a href="{{ route('noticias.show', $noticia) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                @can('editar noticias')<a href="{{ route('noticias.edit', $noticia) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar noticias')
                                <form action="{{ route('noticias.destroy', $noticia) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                </form>
                                @endcan
                            </div></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 20px;">{{ $noticias->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-newspaper"></i><p>No se encontraron noticias.</p></div>
        @endif
    </div>
</div>
@endsection
