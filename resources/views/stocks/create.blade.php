@extends('layouts.app')
@section('title', 'Nuevo Stock - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Registrar Stock</h1>
    <a href="{{ route('stocks.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('stocks.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Referencia <span class="required">*</span></label><input type="text" class="vx-input @error('referencia') is-invalid @enderror" name="referencia" value="{{ old('referencia') }}" required placeholder="REC-001" style="font-family:var(--vx-font-mono);">@error('referencia')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Marca pieza</label><input type="text" class="vx-input" name="marca_pieza" value="{{ old('marca_pieza') }}"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Nombre de la pieza <span class="required">*</span></label><input type="text" class="vx-input @error('nombre_pieza') is-invalid @enderror" name="nombre_pieza" value="{{ old('nombre_pieza') }}" required>@error('nombre_pieza')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="vx-form-group"><label class="vx-label">Descripción</label><textarea class="vx-input" name="descripcion" rows="2">{{ old('descripcion') }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cantidad <span class="required">*</span></label><input type="number" class="vx-input" name="cantidad" value="{{ old('cantidad', 0) }}" min="0" required></div>
            <div class="vx-form-group"><label class="vx-label">Stock mínimo <span class="required">*</span></label><input type="number" class="vx-input" name="stock_minimo" value="{{ old('stock_minimo', 1) }}" min="0" required></div>
            <div class="vx-form-group"><label class="vx-label">Precio (€) <span class="required">*</span></label><input type="number" class="vx-input" name="precio_unitario" value="{{ old('precio_unitario', 0) }}" min="0" step="0.01" required style="font-family:var(--vx-font-mono);"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Ubicación en almacén</label><input type="text" class="vx-input" name="ubicacion_almacen" value="{{ old('ubicacion_almacen') }}" placeholder="Estantería A, Balda 3"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Almacén <span class="required">*</span></label><select class="vx-select" name="almacen_id" required><option value="">Seleccionar...</option>@foreach($almacenes as $a)<option value="{{ $a->id }}" {{ old('almacen_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>@endforeach</select><a href="{{ route('almacenes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required><option value="">Seleccionar...</option>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required><option value="">Seleccionar...</option>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('stocks.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button></div>
    </form>
</div></div></div>
@endsection
