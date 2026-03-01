@extends('layouts.app')
@section('title', 'Editar Tasación - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $tasacion->codigo_tasacion }}</h1><a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('tasaciones.update', $tasacion) }}" method="POST">@csrf @method('PUT')
        <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin-bottom:12px;">DATOS DEL VEHÍCULO</h5>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca vehículo <span class="required">*</span></label><input type="text" class="vx-input" name="vehiculo_marca" value="{{ old('vehiculo_marca', $tasacion->vehiculo_marca) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="vehiculo_modelo" value="{{ old('vehiculo_modelo', $tasacion->vehiculo_modelo) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Año <span class="required">*</span></label><input type="number" class="vx-input" name="vehiculo_anio" value="{{ old('vehiculo_anio', $tasacion->vehiculo_anio) }}" required></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Kilometraje <span class="required">*</span></label><input type="number" class="vx-input" name="kilometraje" value="{{ old('kilometraje', $tasacion->kilometraje) }}" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Matrícula</label><input type="text" class="vx-input" name="matricula" value="{{ old('matricula', $tasacion->matricula) }}" style="text-transform:uppercase;"></div>
            <div class="vx-form-group"><label class="vx-label">Combustible</label><select class="vx-select" name="combustible"><option value="">—</option>@foreach(\App\Models\Tasacion::$combustibles as $c)<option value="{{ $c }}" {{ old('combustible', $tasacion->combustible) == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Estado del Vehículo</label>
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                @foreach(\App\Models\Tasacion::$estadosVehiculo as $k => $v)
                <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;"><input type="radio" name="estado_vehiculo" value="{{ $k }}" {{ old('estado_vehiculo', $tasacion->estado_vehiculo) == $k ? 'checked' : '' }}> {{ $v }}</label>
                @endforeach
            </div>
        </div>
        <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin:16px 0 12px;">VALORACIÓN</h5>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Valor Estimado (€)</label><input type="number" class="vx-input" name="valor_estimado" value="{{ old('valor_estimado', $tasacion->valor_estimado) }}" step="0.01" style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Valor Final (€)</label><input type="number" class="vx-input" name="valor_final" value="{{ old('valor_final', $tasacion->valor_final) }}" step="0.01" style="font-family:var(--vx-font-mono);font-weight:700;"></div>
            <div class="vx-form-group"><label class="vx-label">Estado <span class="required">*</span></label><select class="vx-select" name="estado" required>@foreach(\App\Models\Tasacion::$estados as $k => $v)<option value="{{ $k }}" {{ old('estado', $tasacion->estado) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id', $tasacion->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $tasacion->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Fecha <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_tasacion" value="{{ old('fecha_tasacion', $tasacion->fecha_tasacion->format('Y-m-d')) }}" required></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones', $tasacion->observaciones) }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
