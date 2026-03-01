@extends('layouts.app')
@section('title', 'Editar Coche - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $coches_sustitucion->matricula }}</h1><a href="{{ route('coches-sustitucion.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:650px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('coches-sustitucion.update', $coches_sustitucion) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Matrícula <span class="required">*</span></label><input type="text" class="vx-input" name="matricula" value="{{ old('matricula', $coches_sustitucion->matricula) }}" required style="font-family:var(--vx-font-mono);text-transform:uppercase;"></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="modelo" value="{{ old('modelo', $coches_sustitucion->modelo) }}" required></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca <span class="required">*</span></label><select class="vx-select" name="marca_id" required>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $coches_sustitucion->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Color</label><input type="text" class="vx-input" name="color" value="{{ old('color', $coches_sustitucion->color) }}"></div>
            <div class="vx-form-group"><label class="vx-label">Año</label><input type="number" class="vx-input" name="anio" value="{{ old('anio', $coches_sustitucion->anio) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Taller <span class="required">*</span></label><select class="vx-select" name="taller_id" required>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ old('taller_id', $coches_sustitucion->taller_id) == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select><a href="{{ route('talleres.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $coches_sustitucion->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;"><input type="checkbox" name="disponible" value="1" {{ old('disponible', $coches_sustitucion->disponible) ? 'checked' : '' }}> Disponible</label></div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones', $coches_sustitucion->observaciones) }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('coches-sustitucion.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
