@extends('layouts.app')
@section('title', 'Permisos - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Permisos del Sistema</h1>
    <a href="{{ route('gestion.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Gestión</a>
</div>
<p style="color:var(--vx-text-muted);margin-bottom:20px;">Matriz de permisos asignados a cada rol. Para modificar, edita los roles desde <a href="{{ route('roles.index') }}" style="color:var(--vx-primary);">Roles</a>.</p>

<div class="vx-card">
    <div class="vx-card-body" style="padding:0;overflow-x:auto;">
        <table class="vx-table" style="min-width:700px;">
            <thead>
                <tr>
                    <th style="position:sticky;left:0;background:var(--vx-surface);z-index:2;">Permiso</th>
                    @foreach($roles as $role)
                        <th style="text-align:center;white-space:nowrap;">{{ $role->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grouped = $permissions->groupBy(function($p) {
                        $parts = explode(' ', $p->name);
                        return count($parts) > 1 ? ucfirst($parts[1]) : $p->name;
                    });
                @endphp
                @foreach($grouped as $group => $perms)
                    <tr>
                        <td colspan="{{ $roles->count() + 1 }}" style="background:var(--vx-gray-50);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--vx-text-secondary);padding:8px 16px;">
                            {{ $group }}
                        </td>
                    </tr>
                    @foreach($perms as $permission)
                    <tr>
                        <td style="position:sticky;left:0;background:var(--vx-surface);z-index:1;font-size:13px;">
                            <i class="bi bi-key" style="color:var(--vx-text-muted);margin-right:4px;font-size:11px;"></i>{{ $permission->name }}
                        </td>
                        @foreach($roles as $role)
                            <td style="text-align:center;">
                                @if($role->hasPermissionTo($permission->name))
                                    <i class="bi bi-check-circle-fill" style="color:var(--vx-success);font-size:16px;"></i>
                                @else
                                    <i class="bi bi-x-circle" style="color:var(--vx-gray-400);font-size:14px;"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px;display:flex;gap:16px;flex-wrap:wrap;">
    <div class="vx-card" style="flex:1;min-width:200px;">
        <div class="vx-card-body" style="text-align:center;">
            <div style="font-size:28px;font-weight:800;color:var(--vx-primary);">{{ $permissions->count() }}</div>
            <div style="font-size:12px;color:var(--vx-text-muted);">Permisos totales</div>
        </div>
    </div>
    <div class="vx-card" style="flex:1;min-width:200px;">
        <div class="vx-card-body" style="text-align:center;">
            <div style="font-size:28px;font-weight:800;color:var(--vx-success);">{{ $roles->count() }}</div>
            <div style="font-size:12px;color:var(--vx-text-muted);">Roles del sistema</div>
        </div>
    </div>
</div>
@endsection
