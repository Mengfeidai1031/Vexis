@extends('layouts.app')
@section('title', 'Stock - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Stock de Recambios</h1>
    <div class="vx-page-actions">
        <a href="{{ route('stocks.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a>
        <a href="{{ route('stocks.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @can('crear stocks')<a href="{{ route('stocks.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Registro</a>@endcan
    </div>
</div>
<x-filtros-avanzados :action="route('stocks.index')">
    <div class="vx-filtro" data-filtro="almacen"><label class="vx-filtro-label">Almacén</label><select name="almacen_id" class="vx-select"><option value="">Todos</option>@foreach($almacenes as $a)<option value="{{ $a->id }}" {{ request('almacen_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="activo"><label class="vx-filtro-label">Estado</label><select name="activo" class="vx-select"><option value="">Todos</option><option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option><option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option></select></div>
    <div class="vx-filtro" data-filtro="stock"><label class="vx-filtro-label">Stock</label><select name="bajo_stock" class="vx-select"><option value="">Todos</option><option value="1" {{ request('bajo_stock') ? 'selected' : '' }}>Bajo stock</option></select></div>
</x-filtros-avanzados>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($stocks->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Ref.</th><th>Pieza</th><th>Marca</th><th>Cantidad</th><th>Mín.</th><th>Precio</th><th>Almacén</th><th>Empresa</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($stocks as $s)
            <tr>
                <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ $s->referencia }}</td>
                <td style="font-weight:600;">{{ Str::limit($s->nombre_pieza, 35) }}</td>
                <td style="font-size:12px;">{{ $s->marca_pieza ?? '—' }}</td>
                <td style="text-align:center;">
                    @if($s->isBajoStock())<span class="vx-badge vx-badge-danger">{{ $s->cantidad }}</span>
                    @else<span class="vx-badge vx-badge-success">{{ $s->cantidad }}</span>@endif
                </td>
                <td style="text-align:center;font-size:12px;color:var(--vx-text-muted);">{{ $s->stock_minimo }}</td>
                <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ number_format($s->precio_unitario, 2) }}€</td>
                <td style="font-size:12px;">{{ $s->almacen->nombre ?? '—' }}</td>
                <td style="font-size:12px;">{{ $s->empresa->abreviatura ?? '—' }}</td>
                <td>
                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                        <a href="{{ route('stocks.show', $s) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                        @can('editar stocks')<a href="{{ route('stocks.edit', $s) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                        @can('eliminar stocks')
                        <form action="{{ route('stocks.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>
                        @endcan
                    </div></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $stocks->links('vendor.pagination.vexis') }}</div>
    @else
    <div class="vx-empty"><i class="bi bi-box2"></i><p>No se encontraron registros de stock.</p></div>
    @endif
</div></div>
@endsection
