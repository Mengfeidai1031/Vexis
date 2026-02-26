@extends('layouts.app')
@section('title', 'Catálogo de Precios - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Catálogo de Precios</h1><div class="vx-page-actions">@can('crear catalogo-precios')<a href="{{ route('catalogo-precios.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Modelo</a>@endcan</div></div>

{{-- Tabs de marca --}}
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
    @foreach($marcas as $m)
    <a href="{{ route('catalogo-precios.index', ['marca_id' => $m->id]) }}" class="vx-btn {{ $marcaSeleccionada == $m->id ? 'vx-btn-primary' : 'vx-btn-secondary' }}" style="{{ $marcaSeleccionada == $m->id ? 'background:'.$m->color.';border-color:'.$m->color.';' : '' }}">
        {{ $m->nombre }}
    </a>
    @endforeach
</div>

<form action="{{ route('catalogo-precios.index') }}" method="GET" class="vx-search-box" style="margin-bottom:16px;">
    <input type="hidden" name="marca_id" value="{{ $marcaSeleccionada }}">
    <input type="text" name="search" class="vx-input" placeholder="Buscar modelo o versión..." value="{{ request('search') }}" style="flex:1;">
    <select name="combustible" class="vx-select" style="width:auto;"><option value="">Todos</option>@foreach(\App\Models\CatalogoPrecio::$combustibles as $c)<option value="{{ $c }}" {{ request('combustible') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
</form>

{{-- Grid de modelos --}}
@if($catalogo->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
    @foreach($catalogo as $item)
    <div class="vx-card" style="overflow:hidden;">
        <div style="padding:16px 20px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <h4 style="font-size:16px;font-weight:800;margin:0 0 2px;">{{ $item->modelo }}</h4>
                    <p style="font-size:12px;color:var(--vx-text-muted);margin:0;">{{ $item->version ?? '' }}</p>
                </div>
                @if($item->marca)<span class="vx-badge" style="background:{{ $item->marca->color }}20;color:{{ $item->marca->color }};font-size:10px;">{{ $item->marca->nombre }}</span>@endif
            </div>
            <div style="display:flex;gap:12px;margin-top:12px;">
                @if($item->combustible)<span style="font-size:11px;color:var(--vx-text-muted);"><i class="bi bi-fuel-pump"></i> {{ $item->combustible }}</span>@endif
                @if($item->potencia_cv)<span style="font-size:11px;color:var(--vx-text-muted);"><i class="bi bi-speedometer2"></i> {{ $item->potencia_cv }} CV</span>@endif
                @if($item->anio_modelo)<span style="font-size:11px;color:var(--vx-text-muted);"><i class="bi bi-calendar"></i> {{ $item->anio_modelo }}</span>@endif
            </div>
            <div style="margin-top:14px;display:flex;align-items:baseline;gap:8px;">
                @if($item->precio_oferta)
                <span style="font-size:22px;font-weight:800;color:var(--vx-success);font-family:var(--vx-font-mono);">{{ number_format($item->precio_oferta, 0, ',', '.') }}€</span>
                <span style="font-size:14px;color:var(--vx-text-muted);text-decoration:line-through;font-family:var(--vx-font-mono);">{{ number_format($item->precio_base, 0, ',', '.') }}€</span>
                @php $ahorro = $item->precio_base - $item->precio_oferta; @endphp
                <span class="vx-badge vx-badge-success" style="font-size:10px;">-{{ number_format($ahorro, 0, ',', '.') }}€</span>
                @else
                <span style="font-size:22px;font-weight:800;color:var(--vx-primary);font-family:var(--vx-font-mono);">{{ number_format($item->precio_base, 0, ',', '.') }}€</span>
                @endif
            </div>
            <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center;">
                @if($item->disponible)<span class="vx-badge vx-badge-success">Disponible</span>@else<span class="vx-badge vx-badge-gray">No disponible</span>@endif
                <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                    @can('editar catalogo-precios')<a href="{{ route('catalogo-precios.edit', $item) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                    @can('eliminar catalogo-precios')<form action="{{ route('catalogo-precios.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
                </div></div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div style="margin-top:16px;">{{ $catalogo->links('vendor.pagination.vexis') }}</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-currency-euro"></i><p>No hay modelos en el catálogo para esta marca.</p></div></div></div>
@endif
@endsection
