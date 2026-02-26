@extends('layouts.app')
@section('title', 'Lista de Precios - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-currency-euro" style="color:var(--vx-danger);"></i> Lista de Precios</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach($marcas as $m)
    <a href="{{ route('cliente.precios', ['marca_id' => $m->id]) }}" class="vx-btn {{ $marcaSeleccionada == $m->id ? 'vx-btn-primary' : 'vx-btn-secondary' }}" style="{{ $marcaSeleccionada == $m->id ? 'background:'.$m->color.';border-color:'.$m->color.';' : '' }}">
        {{ $m->nombre }}
    </a>
    @endforeach
</div>

@if($catalogo->count() > 0)
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Modelo</th><th>Versión</th><th>Combustible</th><th>CV</th><th style="text-align:right;">PVP</th><th style="text-align:right;">Oferta</th></tr></thead>
        <tbody>
        @php $currentModel = ''; @endphp
        @foreach($catalogo as $item)
            @if($item->modelo !== $currentModel)
                @php $currentModel = $item->modelo; @endphp
                <tr><td colspan="6" style="padding:10px 16px;font-weight:800;font-size:14px;background:var(--vx-bg);border-bottom:2px solid var(--vx-border);"><i class="bi bi-car-front" style="margin-right:6px;color:{{ $item->marca->color ?? 'var(--vx-primary)' }};"></i>{{ $item->modelo }}</td></tr>
            @endif
            <tr>
                <td></td>
                <td style="font-size:13px;">{{ $item->version ?? '—' }}</td>
                <td style="font-size:12px;">{{ $item->combustible ?? '—' }}</td>
                <td style="font-size:12px;text-align:center;">{{ $item->potencia_cv ?? '—' }}</td>
                <td style="text-align:right;font-family:var(--vx-font-mono);font-size:13px;{{ $item->precio_oferta ? 'text-decoration:line-through;color:var(--vx-text-muted);' : 'font-weight:700;' }}">{{ number_format($item->precio_base, 0, ',', '.') }} €</td>
                <td style="text-align:right;font-family:var(--vx-font-mono);font-size:14px;font-weight:800;color:var(--vx-success);">{{ $item->precio_oferta ? number_format($item->precio_oferta, 0, ',', '.') . ' €' : '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</div></div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-currency-euro"></i><p>No hay modelos disponibles para esta marca.</p></div></div></div>
@endif
@endsection
