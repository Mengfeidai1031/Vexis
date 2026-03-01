@extends('layouts.app')
@section('title', 'Nuevo Mecánico - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Mecánico</h1><a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:600px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('mecanicos.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Nombre <span class="required">*</span></label><input type="text" class="vx-input @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>@error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Apellidos <span class="required">*</span></label><input type="text" class="vx-input @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}" required>@error('apellidos')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Especialidad</label><input type="text" class="vx-input" name="especialidad" value="{{ old('especialidad') }}" placeholder="Ej: Electricidad, Motor, Chapa y pintura"></div>
        <div class="vx-form-group"><label class="vx-label">Taller <span class="required">*</span></label><select class="vx-select @error('taller_id') is-invalid @enderror" name="taller_id" required><option value="">Seleccionar...</option>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ old('taller_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select><a href="{{ route('talleres.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>@error('taller_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('mecanicos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button></div>
    </form>
</div></div></div>
@endsection
