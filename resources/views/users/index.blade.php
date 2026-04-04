@extends('layouts.app')

@section('title', 'Usuarios - VEXIS')

@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Usuarios</h1>
    <div class="vx-page-actions">
        @can('crear usuarios')
            <a href="{{ route('users.create') }}" class="vx-btn vx-btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </a>
        @endcan
    </div>
</div>

<x-filtros-avanzados :action="route('users.index')">
    <div class="vx-filtro" data-filtro="nombre"><label class="vx-filtro-label">Nombre</label><select name="nombre" class="vx-select"><option value="">Todos</option>@foreach($users_all as $u)<option value="{{ $u->nombre_completo }}" {{ request('nombre') == $u->nombre_completo ? 'selected' : '' }}>{{ $u->nombre_completo }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="email"><label class="vx-filtro-label">Email</label><select name="email" class="vx-select"><option value="">Todos</option>@foreach($users_all as $u)<option value="{{ $u->email }}" {{ request('email') == $u->email ? 'selected' : '' }}>{{ $u->email }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="empresa"><label class="vx-filtro-label">Empresa</label><select name="empresa_id" class="vx-select"><option value="">Todas</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ request('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="centro"><label class="vx-filtro-label">Centro</label><select name="centro_id" class="vx-select"><option value="">Todos</option>@foreach($centros as $c)<option value="{{ $c->id }}" {{ request('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="depto"><label class="vx-filtro-label">Departamento</label><select name="departamento_id" class="vx-select"><option value="">Todos</option>@foreach($departamentos as $d)<option value="{{ $d->id }}" {{ request('departamento_id') == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>@endforeach</select></div>
    <div class="vx-filtro" data-filtro="rol"><label class="vx-filtro-label">Rol</label><select name="rol" class="vx-select"><option value="">Todos</option>@foreach($roles as $r)<option value="{{ $r->name }}" {{ request('rol') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>@endforeach</select></div>
</x-filtros-avanzados>

{{-- Tabla --}}
<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($users->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <x-columna-ordenable campo="id" label="ID" />
                            <x-columna-ordenable campo="nombre" label="Nombre" />
                            <x-columna-ordenable campo="email" label="Email" />
                            <x-columna-ordenable campo="empresa_id" label="Empresa" />
                            <x-columna-ordenable campo="departamento_id" label="Departamento" />
                            <x-columna-ordenable campo="centro_id" label="Centro" />
                            <x-columna-ordenable campo="telefono" label="Teléfono" />
                            <th>Restricciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td style="color: var(--vx-text-muted);">{{ $user->id }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $user->nombre_completo }}</div>
                                </td>
                                <td style="font-family: var(--vx-font-mono); font-size: 12px;">{{ $user->email }}</td>
                                <td>{{ $user->empresa->nombre }}</td>
                                <td>{{ $user->departamento->nombre }}</td>
                                <td>{{ $user->centro->nombre }}</td>
                                <td>{{ $user->telefono ?? '—' }}</td>
                                <td>
                                    @if($user->restrictions_count > 0)
                                        <span class="vx-badge vx-badge-warning">{{ $user->restrictions_count }}</span>
                                    @else
                                        <span class="vx-badge vx-badge-success">Sin restricciones</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">@can('view', $user)
                                            <a href="{{ route('users.show', $user) }}" class="vx-btn vx-btn-info vx-btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user) }}" class="vx-btn vx-btn-warning vx-btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('delete', $user)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vx-btn vx-btn-danger vx-btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan</div></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px 20px;">
                {{ $users->links('vendor.pagination.vexis') }}
            </div>
        @else
            <div class="vx-empty">
                <i class="bi bi-people"></i>
                <p>No se encontraron usuarios.</p>
            </div>
        @endif
    </div>
</div>
@endsection
