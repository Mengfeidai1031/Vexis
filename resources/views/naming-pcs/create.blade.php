@extends('layouts.app')
@section('title', 'Nuevo Equipo - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Registrar Nuevo Equipo</h1>
    <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('naming-pcs.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label">Nombre del Equipo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre_equipo') is-invalid @enderror" name="nombre_equipo" value="{{ old('nombre_equipo') }}" required placeholder="ARI-PC-001">
                    @error('nombre_equipo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Tipo <span class="required">*</span></label>
                    <select class="vx-select" name="tipo" required>
                        @foreach(\App\Models\NamingPc::$tipos as $t)
                            <option value="{{ $t }}" {{ old('tipo') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label">Empresa</label>
                    <select class="vx-select" name="empresa_id">
                        <option value="">Sin asignar</option>
                        @foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach
                    </select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva</a>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Centro</label>
                    <select class="vx-select" name="centro_id">
                        <option value="">Sin asignar</option>
                        @foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach
                    </select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Ubicación</label>
                <input type="text" class="vx-input" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Planta 2, Despacho 3">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label">Sistema Operativo</label>
                    <select class="vx-select" name="sistema_operativo">
                        <option value="">—</option>
                        @foreach(\App\Models\NamingPc::$sistemasOperativos as $so)
                            <option value="{{ $so }}" {{ old('sistema_operativo') == $so ? 'selected' : '' }}>{{ $so }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Versión</label>
                    <select class="vx-select" name="version_so">
                        <option value="">—</option>
                        @foreach(\App\Models\NamingPc::$versionesSo as $v)
                            <option value="{{ $v }}" {{ old('version_so') == $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Dirección IP</label>
                    <input type="text" class="vx-input" name="direccion_ip" value="{{ old('direccion_ip') }}" placeholder="192.168.1.100" style="font-family:var(--vx-font-mono);">
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Dirección MAC</label>
                <input type="text" class="vx-input" name="direccion_mac" value="{{ old('direccion_mac') }}" placeholder="AA:BB:CC:DD:EE:FF" style="font-family:var(--vx-font-mono);">
            </div>
            <div class="vx-form-group">
                <label class="vx-label">Observaciones</label>
                <textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
