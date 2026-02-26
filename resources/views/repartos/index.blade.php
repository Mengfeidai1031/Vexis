@extends('layouts.app')
@section('title', 'Repartos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Repartos</h1>
    <div class="vx-page-actions">@can('crear repartos')<a href="{{ route('repartos.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Reparto</a>@endcan</div>
</div>
<form action="{{ route('repartos.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por código o solicitante..." value="{{ request('search') }}" style="flex:1;">
    <select name="estado" class="vx-select" style="width:auto;">
        <option value="">Todos los estados</option>
        @foreach(\App\Models\Reparto::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
    </select>
    <select name="empresa_id" class="vx-select" style="width:auto;">
        <option value="">Todas las empresas</option>
        @foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach
    </select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','estado','empresa_id']))<a href="{{ route('repartos.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($repartos->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Código</th><th>Pieza</th><th>Cantidad</th><th>Origen</th><th>Destino</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($repartos as $r)
            <tr>
                <td style="font-family:var(--vx-font-mono);font-size:11px;">{{ $r->codigo_reparto }}</td>
                <td style="font-weight:600;font-size:13px;">{{ Str::limit($r->stock->nombre_pieza ?? '—', 30) }}</td>
                <td style="text-align:center;font-weight:700;">{{ $r->cantidad }}</td>
                <td style="font-size:12px;">{{ $r->almacenOrigen->nombre ?? '—' }}</td>
                <td style="font-size:12px;">{{ $r->almacenDestino->nombre ?? 'Externo' }}</td>
                <td>
                    @switch($r->estado)
                        @case('pendiente')<span class="vx-badge vx-badge-warning">Pendiente</span>@break
                        @case('en_transito')<span class="vx-badge vx-badge-info">En Tránsito</span>@break
                        @case('entregado')<span class="vx-badge vx-badge-success">Entregado</span>@break
                        @case('cancelado')<span class="vx-badge vx-badge-danger">Cancelado</span>@break
                    @endswitch
                </td>
                <td style="font-size:12px;">{{ $r->fecha_solicitud->format('d/m/Y') }}</td>
                <td>
                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                        <a href="{{ route('repartos.show', $r) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                        @can('editar repartos')<a href="{{ route('repartos.edit', $r) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                        @can('eliminar repartos')
                        <form action="{{ route('repartos.destroy', $r) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>
                        @endcan
                    </div></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $repartos->links('vendor.pagination.vexis') }}</div>
    @else
    <div class="vx-empty"><i class="bi bi-truck"></i><p>No se encontraron repartos.</p></div>
    @endif
</div></div>
@endsection
