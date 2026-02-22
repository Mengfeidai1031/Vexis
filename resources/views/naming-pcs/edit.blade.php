@extends('layouts.app')
@section('title', 'Editar ' . $namingPc->nombre_equipo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $namingPc->nombre_equipo }}</h1>
    <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;">
    <div class="vx-card"><div class="vx-card-body">
        <form action="{{ route('naming-pcs.update', $namingPc) }}" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="nombre_equipo">Nombre del Equipo <span class="required">*</span></label>
                    <input type="text" class="vx-input @error('nombre_equipo') is-invalid @enderror" id="nombre_equipo" name="nombre_equipo" value="{{ old('nombre_equipo', $namingPc->nombre_equipo) }}" required>
                    @error('nombre_equipo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="tipo">Tipo <span class="required">*</span></label>
                    <select class="vx-select" id="tipo" name="tipo" required>
                        @foreach(\App\Models\NamingPc::$tipos as $t)
                            <option value="{{ $t }}" {{ old('tipo', $namingPc->tipo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="empresa_id">Empresa</label>
                    <select class="vx-select" id="empresa_id" name="empresa_id">
                        <option value="">Sin asignar</option>
                        @foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $namingPc->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach
                    </select>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="centro_id">Centro</label>
                    <select class="vx-select" id="centro_id" name="centro_id">
                        <option value="">Sin asignar</option>
                        @foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $namingPc->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="ubicacion">Ubicación</label>
                <input type="text" class="vx-input" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $namingPc->ubicacion) }}">
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="usuario_asignado">Usuario Asignado</label>
                <input type="text" class="vx-input" id="usuario_asignado" name="usuario_asignado" value="{{ old('usuario_asignado', $namingPc->usuario_asignado) }}">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label" for="direccion_ip">Dirección IP</label>
                    <input type="text" class="vx-input" id="direccion_ip" name="direccion_ip" value="{{ old('direccion_ip', $namingPc->direccion_ip) }}" style="font-family:var(--vx-font-mono);">
                </div>
                <div class="vx-form-group">
                    <label class="vx-label" for="direccion_mac">Dirección MAC</label>
                    <input type="text" class="vx-input" id="direccion_mac" name="direccion_mac" value="{{ old('direccion_mac', $namingPc->direccion_mac) }}" style="font-family:var(--vx-font-mono);">
                </div>
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="sistema_operativo">Sistema Operativo</label>
                <input type="text" class="vx-input" id="sistema_operativo" name="sistema_operativo" value="{{ old('sistema_operativo', $namingPc->sistema_operativo) }}">
            </div>
            <div class="vx-form-group">
                <label class="vx-label" for="observaciones">Observaciones</label>
                <textarea class="vx-input" id="observaciones" name="observaciones" rows="2">{{ old('observaciones', $namingPc->observaciones) }}</textarea>
            </div>
            <div class="vx-form-group" style="padding-bottom:4px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', $namingPc->activo) ? 'checked' : '' }}> Equipo activo
                </label>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;">
                <a href="{{ route('naming-pcs.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
