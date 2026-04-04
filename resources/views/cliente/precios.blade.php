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
@php $modelosAgrupados = $catalogo->groupBy('modelo'); @endphp
<div style="display:flex;flex-direction:column;gap:8px;">
    @foreach($modelosAgrupados as $modelo => $versiones)
    <div class="vx-card vx-precios-modelo">
        <div class="vx-precios-modelo-header" onclick="this.closest('.vx-precios-modelo').classList.toggle('open')">
            <div style="display:flex;align-items:center;gap:10px;flex:1;">
                <i class="bi bi-car-front" style="font-size:18px;color:{{ $versiones->first()->marca->color ?? 'var(--vx-primary)' }};"></i>
                <span style="font-weight:800;font-size:15px;">{{ $modelo }}</span>
                <span class="vx-badge" style="background:var(--vx-bg);color:var(--vx-text-muted);font-size:10px;">{{ $versiones->count() }} {{ $versiones->count() === 1 ? 'versión' : 'versiones' }}</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:12px;color:var(--vx-text-muted);">Desde <strong style="color:var(--vx-text);font-size:14px;">{{ number_format($versiones->min('precio_base'), 0, ',', '.') }} €</strong></span>
                <i class="bi bi-chevron-down vx-precios-chevron" style="font-size:12px;color:var(--vx-text-muted);transition:transform 0.2s;"></i>
            </div>
        </div>
        <div class="vx-precios-versiones">
            <table class="vx-table" style="margin:0;">
                <thead><tr><th>Versión</th><th>Combustible</th><th style="text-align:center;">CV</th><th style="text-align:right;">PVP</th><th style="text-align:right;">Oferta</th></tr></thead>
                <tbody>
                @foreach($versiones as $item)
                <tr>
                    <td style="font-size:13px;font-weight:600;">{{ $item->version ?? '—' }}</td>
                    <td style="font-size:12px;">{{ $item->combustible ?? '—' }}</td>
                    <td style="font-size:12px;text-align:center;">{{ $item->potencia_cv ?? '—' }}</td>
                    <td style="text-align:right;font-family:var(--vx-font-mono);font-size:13px;{{ $item->precio_oferta ? 'text-decoration:line-through;color:var(--vx-text-muted);' : 'font-weight:700;' }}">{{ number_format($item->precio_base, 0, ',', '.') }} €</td>
                    <td style="text-align:right;font-family:var(--vx-font-mono);font-size:14px;font-weight:800;color:var(--vx-success);">{{ $item->precio_oferta ? number_format($item->precio_oferta, 0, ',', '.') . ' €' : '' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="vx-card"><div class="vx-card-body"><div class="vx-empty"><i class="bi bi-currency-euro"></i><p>No hay modelos disponibles para esta marca.</p></div></div></div>
@endif

@push('styles')
<style>
.vx-precios-modelo-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; cursor: pointer; user-select: none; transition: background 0.15s; }
.vx-precios-modelo-header:hover { background: var(--vx-bg); }
.vx-precios-versiones { display: none; border-top: 1px solid var(--vx-border); }
.vx-precios-versiones .vx-table { border: none; border-radius: 0; }
.vx-precios-modelo.open .vx-precios-versiones { display: block; }
.vx-precios-modelo.open .vx-precios-chevron { transform: rotate(180deg); }
</style>
@endpush
@endsection
