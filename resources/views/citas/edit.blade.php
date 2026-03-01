@extends('layouts.app')
@section('title', 'Editar Cita - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar Cita: {{ $cita->cliente_nombre }}</h1><a href="{{ route('citas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:700px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('citas.update', $cita) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cliente <span class="required">*</span></label><input type="text" class="vx-input" name="cliente_nombre" value="{{ old('cliente_nombre', $cita->cliente_nombre) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Vehículo</label><input type="text" class="vx-input" name="vehiculo_info" value="{{ old('vehiculo_info', $cita->vehiculo_info) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Fecha <span class="required">*</span></label><input type="date" class="vx-input" name="fecha" value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Hora inicio <span class="required">*</span></label><input type="time" class="vx-input" name="hora_inicio" value="{{ old('hora_inicio', $cita->hora_inicio) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Hora fin</label><input type="time" class="vx-input" name="hora_fin" value="{{ old('hora_fin', $cita->hora_fin) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Mecánico <span class="required">*</span></label><select class="vx-select" name="mecanico_id" required>@foreach($mecanicos as $m)<option value="{{ $m->id }}" {{ old('mecanico_id', $cita->mecanico_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre_completo }}</option>@endforeach</select><a href="{{ route('mecanicos.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Taller <span class="required">*</span></label><select class="vx-select" name="taller_id" required>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ old('taller_id', $cita->taller_id) == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select><a href="{{ route('talleres.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id"><option value="">Sin marca</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $cita->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $cita->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Estado <span class="required">*</span></label><select class="vx-select" name="estado" required>@foreach(\App\Models\CitaTaller::$estados as $k => $v)<option value="{{ $k }}" {{ old('estado', $cita->estado) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Descripción</label><textarea class="vx-input" name="descripcion" rows="2">{{ old('descripcion', $cita->descripcion) }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('citas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
