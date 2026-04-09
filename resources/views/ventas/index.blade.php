@extends('layouts.app')
@section('title', 'Ventas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Ventas</h1><div class="vx-page-actions"><a href="{{ route('ventas.export') }}" class="vx-btn vx-btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</a><a href="{{ route('ventas.exportPdf') }}" class="vx-btn vx-btn-danger"><i class="bi bi-file-earmark-pdf"></i> PDF</a>@can('crear ventas')<a href="{{ route('ventas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Venta</a>@endcan</div></div>
<x-filtros-avanzados :action="route('ventas.index')">
    <div class="vx-filtro" data-filtro="codigo"><label class="vx-filtro-label">Código</label><select name="codigo_venta" class="vx-select"><option value="">Todos</option>@foreach($codigos_venta as $c)<option value="{{ $c }}" {{ request('codigo_venta') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="vehiculo_modelo"><label class="vx-filtro-label">Modelo Vehículo</label><select name="vehiculo_modelo" class="vx-select"><option value="">Todos</option>@foreach($modelos_vehiculo as $m)<option value="{{ $m }}" {{ request('vehiculo_modelo') == $m ? 'selected' : '' }}>{{ $m }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="cliente"><label class="vx-filtro-label">Cliente</label><select name="cliente_id" class="vx-select"><option value="">Todos</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre_completo }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="marca"><label class="vx-filtro-label">Marca</label><select name="marca_id" class="vx-select"><option value="">Todas</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="precio_min"><label class="vx-filtro-label">Precio mín.</label><input type="number" step="0.01" name="precio_min" class="vx-input" value="{{ request('precio_min') }}"></div>
    <div class="vx-filtro" data-filtro="precio_max"><label class="vx-filtro-label">Precio máx.</label><input type="number" step="0.01" name="precio_max" class="vx-input" value="{{ request('precio_max') }}"></div>
    <div class="vx-filtro" data-filtro="pago"><label class="vx-filtro-label">Forma de pago</label><select name="forma_pago" class="vx-select"><option value="">Todas</option>@foreach(\App\Models\Venta::$formasPago as $k => $v)<option value="{{ $k }}" {{ request('forma_pago') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="estado"><label class="vx-filtro-label">Estado</label><select name="estado" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\Venta::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="desde"><label class="vx-filtro-label">Fecha desde</label><input type="date" name="fecha_desde" class="vx-input" value="{{ request('fecha_desde') }}"></div>
    <div class="vx-filtro" data-filtro="hasta"><label class="vx-filtro-label">Fecha hasta</label><input type="date" name="fecha_hasta" class="vx-input" value="{{ request('fecha_hasta') }}"></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="vendedor"><label class="vx-filtro-label">Vendedor</label><select name="vendedor_id" class="vx-select"><option value="">Todos</option>@foreach($vendedores as $v)<option value="{{ $v->id }}" {{ request('vendedor_id') == $v->id ? 'selected' : '' }}>{{ $v->nombre_completo }}</option>@endforeach</select></div>
</x-filtros-avanzados>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($ventas->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><x-columna-ordenable campo="codigo_venta" label="Código" /><x-columna-ordenable campo="vehiculo_id" label="Vehículo" /><x-columna-ordenable campo="cliente_id" label="Cliente" /><x-columna-ordenable campo="marca_id" label="Marca" /><x-columna-ordenable campo="precio_final" label="Total" /><x-columna-ordenable campo="forma_pago" label="Pago" /><x-columna-ordenable campo="estado" label="Estado" /><x-columna-ordenable campo="fecha_venta" label="Fecha" /><th>Acciones</th></tr></thead>
        <tbody>@foreach($ventas as $v)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $v->codigo_venta }}</td>
            <td style="font-weight:600;font-size:13px;">{{ Str::limit($v->vehiculo->modelo ?? '—', 25) }}</td>
            <td style="font-size:12px;">{{ $v->cliente->nombre ?? '—' }} {{ $v->cliente->apellidos ?? '' }}</td>
            <td>@if($v->marca)@php $logoSlug = Str::lower($v->marca->nombre); @endphp<span class="vx-badge" style="background:{{ $v->marca->color }}20;color:{{ $v->marca->color }};display:inline-flex;align-items:center;gap:4px;">@if(file_exists(storage_path("app/public/logos/{$logoSlug}.png")))<img src="{{ asset("storage/logos/{$logoSlug}.png") }}" alt="" style="height:14px;">@endif{{ $v->marca->nombre }}</span>@endif</td>
            <td style="font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($v->total ?? $v->precio_final, 2, ',', '.') }} €</td>
            <td style="font-size:11px;">{{ \App\Models\Venta::$formasPago[$v->forma_pago] ?? $v->forma_pago }}</td>
            <td>@switch($v->estado) @case('reservada')<span class="vx-badge vx-badge-warning">Reservada</span>@break @case('pendiente_entrega')<span class="vx-badge vx-badge-info">Pte. Entrega</span>@break @case('entregada')<span class="vx-badge vx-badge-success">Entregada</span>@break @case('cancelada')<span class="vx-badge vx-badge-danger">Cancelada</span>@break @endswitch</td>
            <td style="font-size:12px;">{{ $v->fecha_venta->format('d/m/Y') }}</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                <a href="{{ route('ventas.show', $v) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                @can('editar ventas')<a href="{{ route('ventas.edit', $v) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar ventas')<form action="{{ route('ventas.destroy', $v) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $ventas->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-cart-check"></i><p>No se encontraron ventas.</p></div>@endif
</div></div>
@endsection
