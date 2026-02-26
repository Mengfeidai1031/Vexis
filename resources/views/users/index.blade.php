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

{{-- Buscador --}}
<form action="{{ route('users.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar por nombre, email, empresa, departamento o centro..." value="{{ request('search') }}">
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i> Buscar</button>
    @if(request('search'))
        <a href="{{ route('users.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>
    @endif
</form>

{{-- Tabla --}}
<div class="vx-card">
    <div class="vx-card-body" style="padding: 0;">
        @if($users->count() > 0)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Departamento</th>
                            <th>Centro</th>
                            <th>Teléfono</th>
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
