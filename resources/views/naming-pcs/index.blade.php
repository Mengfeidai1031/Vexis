@extends('layouts.app')
@section('title', 'Naming PCs - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Naming PCs</h1>
    <div class="vx-page-actions">
        @can('crear naming-pcs')
            <a href="{{ route('naming-pcs.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Equipo</a>
        @endcan
    </div>
</div>
<form action="{{ route('naming-pcs.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, IP o ubicación..." value="{{ request('search') }}" style="flex:1;">
    <select name="tipo" class="vx-select" style="width:auto;">
        <option value="">Todos los tipos</option>
        @foreach(\App\Models\NamingPc::$tipos as $t)
            <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>
    <select name="empresa_id" class="vx-select" style="width:auto;">
        <option value="">Todas las empresas</option>
        @foreach($empresas as $e)
            <option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
        @endforeach
    </select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','tipo','empresa_id']))<a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>
<div class="vx-card">
    <div class="vx-card-body" style="padding:0;">
        @if($namingPcs->count() > 0)
        <div class="vx-table-wrapper">
            <table class="vx-table">
                <thead><tr><th>Nombre</th><th>Tipo</th><th>IP</th><th>Empresa</th><th>Centro</th><th>SO</th><th>Versión</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @foreach($namingPcs as $pc)
                    <tr>
                        <td style="font-weight:600;"><i class="bi bi-pc-display" style="color:var(--vx-primary);margin-right:4px;"></i>{{ $pc->nombre_equipo }}</td>
                        <td><span class="vx-badge vx-badge-info">{{ $pc->tipo }}</span></td>
                        <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ $pc->direccion_ip ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $pc->empresa->abreviatura ?? '—' }}</td>
                        <td style="font-size:12px;">{{ $pc->centro->nombre ?? '—' }}</td>
                        
                        <td style="font-size:11px;">{{ $pc->sistema_operativo ?? '—' }}</td>
                        <td style="font-size:11px;">{{ $pc->version_so ?? '—' }}</td>
                        <td>
                            @if($pc->activo)<span class="vx-badge vx-badge-success">Activo</span>
                            @else<span class="vx-badge vx-badge-gray">Inactivo</span>@endif
                        </td>
                        <td>
                            <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                                <a href="{{ route('naming-pcs.show', $pc) }}"><i class="bi bi-eye" style="color:var(--vx-info);"></i> Ver</a>
                                @can('editar naming-pcs')<a href="{{ route('naming-pcs.edit', $pc) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                                @can('eliminar naming-pcs')
                                <form action="{{ route('naming-pcs.destroy', $pc) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
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
        <div style="padding:16px 20px;">{{ $namingPcs->links('vendor.pagination.vexis') }}</div>
        @else
        <div class="vx-empty"><i class="bi bi-pc-display"></i><p>No se encontraron equipos.</p></div>
        @endif
    </div>
</div>
@endsection
