@extends('layouts.app')
@section('title', 'Editar Taller - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $taller->nombre }}</h1><a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('talleres.update', $taller) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Nombre <span class="required">*</span></label><input type="text" class="vx-input" name="nombre" value="{{ old('nombre', $taller->nombre) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Código <span class="required">*</span></label><input type="text" class="vx-input" name="codigo" value="{{ old('codigo', $taller->codigo) }}" required style="font-family:var(--vx-font-mono);"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Domicilio <span class="required">*</span></label><input type="text" class="vx-input" name="domicilio" value="{{ old('domicilio', $taller->domicilio) }}" required></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Código Postal</label><input type="text" class="vx-input" name="codigo_postal" value="{{ old('codigo_postal', $taller->codigo_postal) }}" maxlength="5"></div>
            <div class="vx-form-group"><label class="vx-label">Localidad</label><input type="text" class="vx-input" name="localidad" value="{{ old('localidad', $taller->localidad) }}"></div>
            <div class="vx-form-group"><label class="vx-label">Isla</label><select class="vx-select" name="isla"><option value="">Seleccionar...</option>@foreach(\App\Models\Taller::$islas as $i)<option value="{{ $i }}" {{ old('isla', $taller->isla) == $i ? 'selected' : '' }}>{{ $i }}</option>@endforeach</select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Teléfono</label><input type="text" class="vx-input" name="telefono" value="{{ old('telefono', $taller->telefono) }}"></div>
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id"><option value="">Sin marca</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $taller->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $taller->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $taller->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:120px 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Capacidad</label><input type="number" class="vx-input" name="capacidad_diaria" value="{{ old('capacidad_diaria', $taller->capacidad_diaria) }}" min="1" required></div>
            <div class="vx-form-group" style="display:flex;align-items:flex-end;padding-bottom:12px;"><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;"><input type="checkbox" name="activo" value="1" {{ old('activo', $taller->activo) ? 'checked' : '' }}> Activo</label></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones', $taller->observaciones) }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
