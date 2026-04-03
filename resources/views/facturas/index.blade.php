@extends('layouts.app')
@section('title', 'Facturas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Facturas</h1><div class="vx-page-actions"><a href="{{ route('facturas.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a><a href="{{ route('facturas.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>@can('crear facturas')<a href="{{ route('facturas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Factura</a>@endcan</div></div>
<x-filtros-avanzados :action="route('facturas.index')">
    <div class="vx-filtro" data-filtro="estado"><label class="vx-filtro-label">Estado</label><select name="estado" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Factura::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="cliente"><label class="vx-filtro-label">Cliente</label><select name="cliente_id" class="vx-select"><option value="">Todos</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre_completo }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="marca"><label class="vx-filtro-label">Marca</label><select name="marca_id" class="vx-select"><option value="">Todas</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="desde"><label class="vx-filtro-label">Fecha desde</label><input type="date" name="fecha_desde" class="vx-input" value="{{ request('fecha_desde') }}"></div>
    <div class="vx-filtro" data-filtro="hasta"><label class="vx-filtro-label">Fecha hasta</label><input type="date" name="fecha_hasta" class="vx-input" value="{{ request('fecha_hasta') }}"></div>
</x-filtros-avanzados>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($facturas->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Cliente</th><th>Marca</th><th>Concepto</th><th>Subtotal</th><th>IVA</th><th>Total</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($facturas as $f)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $f->codigo_factura }}</td>
            <td style="font-size:12px;">{{ $f->cliente ? $f->cliente->nombre . ' ' . $f->cliente->apellidos : '—' }}</td>
            <td>@if($f->marca)@php $logoSlug = Str::lower($f->marca->nombre); @endphp<span class="vx-badge" style="background:{{ $f->marca->color }}20;color:{{ $f->marca->color }};display:inline-flex;align-items:center;gap:4px;">@if(file_exists(storage_path("app/public/logos/{$logoSlug}.png")))<img src="{{ asset("storage/logos/{$logoSlug}.png") }}" alt="" style="height:14px;">@endif{{ $f->marca->nombre }}</span>@endif</td>
            <td style="font-size:12px;">{{ Str::limit($f->concepto ?? '—', 30) }}</td>
            <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ number_format($f->subtotal, 2) }}€</td>
            <td style="font-family:var(--vx-font-mono);font-size:11px;color:var(--vx-text-muted);">{{ number_format($f->iva_importe, 2) }}€</td>
            <td style="font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($f->total, 2) }}€</td>
            <td>@switch($f->estado) @case('emitida')<span class="vx-badge vx-badge-info">Emitida</span>@break @case('pagada')<span class="vx-badge vx-badge-success">Pagada</span>@break @case('vencida')<span class="vx-badge vx-badge-warning">Vencida</span>@break @case('anulada')<span class="vx-badge vx-badge-danger">Anulada</span>@break @endswitch</td>
            <td style="font-size:12px;">{{ $f->fecha_factura->format('d/m/Y') }}</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('facturas.show', $f) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                <a href="{{ route('facturas.generatePdf', $f) }}"><i class="bi bi-file-earmark-pdf" style="color:var(--vx-danger);"></i> Generar PDF</a>
                @can('editar facturas')<a href="{{ route('facturas.edit', $f) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar facturas')<form action="{{ route('facturas.destroy', $f) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta factura?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $facturas->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-receipt"></i><p>No se encontraron facturas.</p></div>@endif
</div></div>
@endsection
