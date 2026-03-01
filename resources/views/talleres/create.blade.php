@extends('layouts.app')
@section('title', 'Nuevo Taller - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Crear Nuevo Taller</h1><a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('talleres.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Nombre <span class="required">*</span></label><input type="text" class="vx-input @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>@error('nombre')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Código <span class="required">*</span></label><input type="text" class="vx-input @error('codigo') is-invalid @enderror" name="codigo" value="{{ old('codigo') }}" required placeholder="TAL-XXX-XX" style="font-family:var(--vx-font-mono);">@error('codigo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Domicilio <span class="required">*</span></label><input type="text" class="vx-input" name="domicilio" value="{{ old('domicilio') }}" required></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Código Postal</label><input type="text" class="vx-input" name="codigo_postal" value="{{ old('codigo_postal') }}" maxlength="5"></div>
            <div class="vx-form-group"><label class="vx-label">Localidad</label><input type="text" class="vx-input" name="localidad" value="{{ old('localidad') }}"></div>
            <div class="vx-form-group"><label class="vx-label">Isla</label><select class="vx-select" name="isla"><option value="">Seleccionar...</option>@foreach(\App\Models\Taller::$islas as $i)<option value="{{ $i }}" {{ old('isla') == $i ? 'selected' : '' }}>{{ $i }}</option>@endforeach</select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Teléfono</label><input type="text" class="vx-input" name="telefono" value="{{ old('telefono') }}"></div>
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id"><option value="">Sin marca</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Capacidad diaria <span class="required">*</span></label><input type="number" class="vx-input" name="capacidad_diaria" value="{{ old('capacidad_diaria', 10) }}" min="1" required style="max-width:120px;"></div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button></div>
    </form>
</div></div></div>
@endsection
