@extends('layouts.app')
@section('title', 'Editar Stock - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar: {{ $stock->nombre_pieza }}</h1>
    <a href="{{ route('stocks.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>
<div style="max-width:750px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('stocks.update', $stock) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Referencia <span class="required">*</span></label><input type="text" class="vx-input" name="referencia" value="{{ old('referencia', $stock->referencia) }}" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Marca pieza</label><input type="text" class="vx-input" name="marca_pieza" value="{{ old('marca_pieza', $stock->marca_pieza) }}"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Nombre de la pieza <span class="required">*</span></label><input type="text" class="vx-input" name="nombre_pieza" value="{{ old('nombre_pieza', $stock->nombre_pieza) }}" required></div>
        <div class="vx-form-group"><label class="vx-label">Descripción</label><textarea class="vx-input" name="descripcion" rows="2">{{ old('descripcion', $stock->descripcion) }}</textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Cantidad <span class="required">*</span></label><input type="number" class="vx-input" name="cantidad" value="{{ old('cantidad', $stock->cantidad) }}" min="0" required></div>
            <div class="vx-form-group"><label class="vx-label">Stock mínimo <span class="required">*</span></label><input type="number" class="vx-input" name="stock_minimo" value="{{ old('stock_minimo', $stock->stock_minimo) }}" min="0" required></div>
            <div class="vx-form-group"><label class="vx-label">Precio (€) <span class="required">*</span></label><input type="number" class="vx-input" name="precio_unitario" value="{{ old('precio_unitario', $stock->precio_unitario) }}" min="0" step="0.01" required style="font-family:var(--vx-font-mono);"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Ubicación en almacén</label><input type="text" class="vx-input" name="ubicacion_almacen" value="{{ old('ubicacion_almacen', $stock->ubicacion_almacen) }}"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Almacén <span class="required">*</span></label><select class="vx-select" name="almacen_id" required>@foreach($almacenes as $a)<option value="{{ $a->id }}" {{ old('almacen_id', $stock->almacen_id) == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>@endforeach</select><a href="{{ route('almacenes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id', $stock->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id', $stock->centro_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;"><input type="checkbox" name="activo" value="1" {{ old('activo', $stock->activo) ? 'checked' : '' }}> Activo</label></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('stocks.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
