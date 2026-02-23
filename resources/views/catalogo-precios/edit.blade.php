@extends('layouts.app')
@section('title', 'Editar Modelo - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Editar: {{ $catalogo_precio->modelo }} {{ $catalogo_precio->version }}</h1><a href="{{ route('catalogo-precios.index', ['marca_id' => $catalogo_precio->marca_id]) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:650px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('catalogo-precios.update', $catalogo_precio) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca <span class="required">*</span></label><select class="vx-select" name="marca_id" required>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id', $catalogo_precio->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="modelo" value="{{ old('modelo', $catalogo_precio->modelo) }}" required></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Versión</label><input type="text" class="vx-input" name="version" value="{{ old('version', $catalogo_precio->version) }}"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Combustible</label><select class="vx-select" name="combustible"><option value="">—</option>@foreach(\App\Models\CatalogoPrecio::$combustibles as $c)<option value="{{ $c }}" {{ old('combustible', $catalogo_precio->combustible) == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Potencia (CV)</label><input type="number" class="vx-input" name="potencia_cv" value="{{ old('potencia_cv', $catalogo_precio->potencia_cv) }}"></div>
            <div class="vx-form-group"><label class="vx-label">Año modelo</label><input type="number" class="vx-input" name="anio_modelo" value="{{ old('anio_modelo', $catalogo_precio->anio_modelo) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Precio Base (€) <span class="required">*</span></label><input type="number" class="vx-input" name="precio_base" value="{{ old('precio_base', $catalogo_precio->precio_base) }}" step="0.01" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Precio Oferta (€)</label><input type="number" class="vx-input" name="precio_oferta" value="{{ old('precio_oferta', $catalogo_precio->precio_oferta) }}" step="0.01" style="font-family:var(--vx-font-mono);color:var(--vx-success);"></div>
        </div>
        <div class="vx-form-group"><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;"><input type="checkbox" name="disponible" value="1" {{ old('disponible', $catalogo_precio->disponible) ? 'checked' : '' }}> Disponible</label></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('catalogo-precios.index', ['marca_id' => $catalogo_precio->marca_id]) }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Actualizar</button></div>
    </form>
</div></div></div>
@endsection
