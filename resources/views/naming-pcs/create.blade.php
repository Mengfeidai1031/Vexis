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
                    <label class="vx-label" for="nombre_equipo">Nombre del Equipo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre_equipo') is-invalid @enderror" id="nombre_equipo" name="nombre_equipo" value="{{ old('nombre_equipo') }}" required placeholder="ARI-PC-001">
                    @error('nombre_equipo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="tipo">Tipo <span class="required">*</span></label>
                    <select class="vx-select" id="tipo" name="tipo" required>
                        @foreach(\App\Models\NamingPc::$tipos as $t)
                            <option value="{{ $t }}" {{ old('tipo') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa</label>
                    <select class="vx-select" id="empresa_id" name="empresa_id">
                        <option value="">Sin asignar</option>
                        @foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach
                    </select>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="centro_id">Centro</label>
                    <select class="vx-select" id="centro_id" name="centro_id">
                        <option value="">Sin asignar</option>
                        @foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="ubicacion">Ubicación</label>
                <input type="text" class="vx-input" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Planta 2, Despacho 3">
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="usuario_asignado">Usuario Asignado</label>
                <input type="text" class="vx-input" id="usuario_asignado" name="usuario_asignado" value="{{ old('usuario_asignado') }}">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="direccion_ip">Dirección IP</label>
                    <input type="text" class="vx-input" id="direccion_ip" name="direccion_ip" value="{{ old('direccion_ip') }}" placeholder="192.168.1.100" style="font-family:var(--vx-font-mono);">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="direccion_mac">Dirección MAC</label>
                    <input type="text" class="vx-input" id="direccion_mac" name="direccion_mac" value="{{ old('direccion_mac') }}" placeholder="AA:BB:CC:DD:EE:FF" style="font-family:var(--vx-font-mono);">
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="sistema_operativo">Sistema Operativo</label>
                <input type="text" class="vx-input" id="sistema_operativo" name="sistema_operativo" value="{{ old('sistema_operativo') }}" placeholder="Windows 11 Pro">
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="observaciones">Observaciones</label>
                <textarea class="vx-input" id="observaciones" name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
