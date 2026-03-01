@extends('layouts.app')
@section('title', 'Nuevo Reparto - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Crear Nuevo Reparto</h1>
    <a href="{{ route('repartos.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('repartos.store') }}" method="POST">@csrf
        <div class="vx-form-group"><label class="vx-label">Pieza (Stock) <span class="required">*</span></label>
            <select class="vx-select @error('stock_id') is-invalid @enderror" name="stock_id" required>
                <option value="">Seleccionar pieza...</option>
                @foreach($stocks as $s)<option value="{{ $s->id }}" {{ old('stock_id') == $s->id ? 'selected' : '' }}>{{ $s->referencia }} — {{ $s->nombre_pieza }} ({{ $s->cantidad }} uds.)</option>@endforeach
            </select>@error('stock_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="vx-form-group"><label class="vx-label">Cantidad <span class="required">*</span></label><input type="number" class="vx-input @error('cantidad') is-invalid @enderror" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required>@error('cantidad')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Almacén Origen <span class="required">*</span></label>
                <select class="vx-select" name="almacen_origen_id" required><option value="">Seleccionar...</option>@foreach($almacenes as $a)<option value="{{ $a->id }}" {{ old('almacen_origen_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>@endforeach</select><a href="{{ route('almacenes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
            </div>
            <div class="vx-form-group"><label class="vx-label">Almacén Destino</label>
                <select class="vx-select" name="almacen_destino_id"><option value="">Externo / Sin destino</option>@foreach($almacenes as $a)<option value="{{ $a->id }}" {{ old('almacen_destino_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>@endforeach</select><a href="{{ route('almacenes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required><option value="">Seleccionar...</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required><option value="">Seleccionar...</option>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Fecha solicitud <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_solicitud" value="{{ old('fecha_solicitud', date('Y-m-d')) }}" required></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Solicitado por</label><input type="text" class="vx-input" name="solicitado_por" value="{{ old('solicitado_por', Auth::user()->nombre_completo) }}"></div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('repartos.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Crear Reparto</button></div>
    </form>
</div></div></div>
@endsection
