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
<x-filtros-avanzados :action="route('naming-pcs.index')">
    <div class="vx-filtro" data-filtro="tipo"><label class="vx-filtro-label">Tipo</label><select name="tipo" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\NamingPc::$tipos as $t)<option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="centro"><label class="vx-filtro-label">Centro</label><select name="centro_id" class="vx-select"><option value="">Todos</option>@foreach($centros as $c)<option value="{{ $c->id }}" {{ request('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="so"><label class="vx-filtro-label">Sist. Operativo</label><select name="sistema_operativo" class="vx-select"><option value="">Todos</option>@foreach(\App\Models\NamingPc::$sistemasOperativos as $so)<option value="{{ $so }}" {{ request('sistema_operativo') == $so ? 'selected' : '' }}>{{ $so }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="activo"><label class="vx-filtro-label">Estado</label><select name="activo" class="vx-select"><option value="">Todos</option><option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option><option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option></select></div>
</x-filtros-avanzados>
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
