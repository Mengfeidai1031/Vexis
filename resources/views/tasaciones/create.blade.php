@extends('layouts.app')
@section('title', 'Nueva Tasación - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Crear Tasación</h1><a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('tasaciones.store') }}" method="POST">@csrf
        <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin-bottom:12px;">DATOS DEL VEHÍCULO</h5>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca vehículo <span class="required">*</span></label><input type="text" class="vx-input" name="vehiculo_marca" value="{{ old('vehiculo_marca') }}" required placeholder="Ej: Nissan"></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="vehiculo_modelo" value="{{ old('vehiculo_modelo') }}" required placeholder="Ej: Qashqai"></div>
            <div class="vx-form-group"><label class="vx-label">Año <span class="required">*</span></label><input type="number" class="vx-input" name="vehiculo_anio" value="{{ old('vehiculo_anio', date('Y')) }}" min="1990" max="2030" required></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Kilometraje <span class="required">*</span></label><input type="number" class="vx-input" name="kilometraje" value="{{ old('kilometraje') }}" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Matrícula</label><input type="text" class="vx-input" name="matricula" value="{{ old('matricula') }}" style="text-transform:uppercase;"></div>
            <div class="vx-form-group"><label class="vx-label">Combustible</label><select class="vx-select" name="combustible"><option value="">—</option>@foreach(\App\Models\Tasacion::$combustibles as $c)<option value="{{ $c }}" {{ old('combustible') == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Estado del Vehículo <span class="required">*</span></label>
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                @foreach(\App\Models\Tasacion::$estadosVehiculo as $k => $v)
                <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;"><input type="radio" name="estado_vehiculo" value="{{ $k }}" {{ old('estado_vehiculo', 'bueno') == $k ? 'checked' : '' }}> {{ $v }}</label>
                @endforeach
            </div>
        </div>
        <h5 style="font-size:13px;font-weight:700;color:var(--vx-text-muted);margin:16px 0 12px;">DATOS DE LA TASACIÓN</h5>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Fecha <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_tasacion" value="{{ old('fecha_tasacion', date('Y-m-d')) }}" required></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Valor Estimado (€)</label><input type="number" class="vx-input" name="valor_estimado" value="{{ old('valor_estimado') }}" step="0.01" min="0" style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Marca concesionario</label><select class="vx-select" name="marca_id"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('tasaciones.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Crear Tasación</button></div>
    </form>
</div></div></div>
@endsection
