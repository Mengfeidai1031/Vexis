@extends('layouts.app')
@section('title', 'Editar Mecánico - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $mecanico->nombre_completo }}</h1><a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:600px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('mecanicos.update', $mecanico) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Nombre <span class="required">*</span></label><input type="text" class="vx-input" name="nombre" value="{{ old('nombre', $mecanico->nombre) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Apellidos <span class="required">*</span></label><input type="text" class="vx-input" name="apellidos" value="{{ old('apellidos', $mecanico->apellidos) }}" required></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Especialidad</label><input type="text" class="vx-input" name="especialidad" value="{{ old('especialidad', $mecanico->especialidad) }}"></div>
        <div class="vx-form-group"><label class="vx-label">Taller <span class="required">*</span></label><select class="vx-select" name="taller_id" required>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ old('taller_id', $mecanico->taller_id) == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select><a href="{{ route('talleres.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        <div class="vx-form-group"><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;"><input type="checkbox" name="activo" value="1" {{ old('activo', $mecanico->activo) ? 'checked' : '' }}> Activo</label></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
