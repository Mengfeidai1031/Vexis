@extends('layouts.app')
@section('title', 'Ventas - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Ventas</h1><div class="vx-page-actions">@can('crear ventas')<a href="{{ route('ventas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Venta</a>@endcan</div></div>
<form action="{{ route('ventas.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por código o cliente..." value="{{ request('search') }}" style="flex:1;">
    <select name="estado" class="vx-select" style="width:auto;"><option value="">Todos</option>@foreach(\App\Models\Venta::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <select name="marca_id" class="vx-select" style="width:auto;"><option value="">Todas las marcas</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','estado','marca_id']))<a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($ventas->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Vehículo</th><th>Cliente</th><th>Marca</th><th>Precio Final</th><th>Pago</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($ventas as $v)
        <tr>
            <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $v->codigo_venta }}</td>
            <td style="font-weight:600;font-size:13px;">{{ Str::limit($v->vehiculo->modelo ?? '—', 25) }}</td>
            <td style="font-size:12px;">{{ $v->cliente->nombre ?? '—' }} {{ $v->cliente->apellidos ?? '' }}</td>
            <td>@if($v->marca)<span class="vx-badge" style="background:{{ $v->marca->color }}20;color:{{ $v->marca->color }};">{{ $v->marca->nombre }}</span>@endif</td>
            <td style="font-family:var(--vx-font-mono);font-weight:700;">{{ number_format($v->precio_final, 2) }}€</td>
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
