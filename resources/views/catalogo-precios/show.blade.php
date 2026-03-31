@extends('layouts.app')
@section('title', $catalogo_precio->modelo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $catalogo_precio->modelo }}</h1>
    <div class="vx-page-actions">@can('editar catalogo-precios')<a href="{{ route('catalogo-precios.edit', $catalogo_precio) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('catalogo-precios.index', ['marca_id' => $catalogo_precio->marca_id]) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div style="max-width:700px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Detalles del Modelo</h4></div><div class="vx-card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($catalogo_precio->marca)<span class="vx-badge" style="background:{{ $catalogo_precio->marca->color }}20;color:{{ $catalogo_precio->marca->color }};">{{ $catalogo_precio->marca->nombre }}</span>@else — @endif</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Modelo</div><div class="vx-info-value" style="font-weight:700;">{{ $catalogo_precio->modelo }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Versión</div><div class="vx-info-value">{{ $catalogo_precio->version ?? '—' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Combustible</div><div class="vx-info-value">{{ $catalogo_precio->combustible ?? '—' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Potencia</div><div class="vx-info-value">{{ $catalogo_precio->potencia_cv ? $catalogo_precio->potencia_cv . ' CV' : '—' }}</div></div>
            </div>
            <div>
                <div class="vx-info-row"><div class="vx-info-label">Precio Base</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:700;font-size:18px;">{{ number_format($catalogo_precio->precio_base, 2, ',', '.') }} €</div></div>
                @if($catalogo_precio->precio_oferta)
                <div class="vx-info-row"><div class="vx-info-label">Precio Oferta</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);font-weight:700;color:var(--vx-success);">{{ number_format($catalogo_precio->precio_oferta, 2, ',', '.') }} €</div></div>
                @endif
                <div class="vx-info-row"><div class="vx-info-label">Año Modelo</div><div class="vx-info-value">{{ $catalogo_precio->anio_modelo ?? '—' }}</div></div>
                <div class="vx-info-row"><div class="vx-info-label">Disponible</div><div class="vx-info-value">@if($catalogo_precio->disponible)<span class="vx-badge vx-badge-success">Sí</span>@else<span class="vx-badge vx-badge-gray">No</span>@endif</div></div>
            </div>
        </div>
    </div></div>
</div>
@endsection
