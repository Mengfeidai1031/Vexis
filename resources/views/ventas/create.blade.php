@extends('layouts.app')
@section('title', 'Nueva Venta - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Venta</h1><a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:800px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('ventas.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Vehículo <span class="required">*</span></label><select class="vx-select @error('vehiculo_id') is-invalid @enderror" name="vehiculo_id" required><option value="">Seleccionar...</option>@foreach($vehiculos as $v)<option value="{{ $v->id }}" {{ old('vehiculo_id') == $v->id ? 'selected' : '' }}>{{ $v->modelo }} {{ $v->matricula ? '('.$v->matricula.')' : '' }}</option>@endforeach</select><a href="{{ route('vehiculos.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>@error('vehiculo_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Cliente</label><select class="vx-select" name="cliente_id"><option value="">Sin asignar</option>@foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach</select><a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca</label><select class="vx-select" name="marca_id"><option value="">—</option>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Centro <span class="required">*</span></label><select class="vx-select" name="centro_id" required>@foreach($centros as $c)<option value="{{ $c->id }}" {{ old('centro_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>@endforeach</select><a href="{{ route('centros.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Precio Venta (€) <span class="required">*</span></label><input type="number" class="vx-input" id="precioVenta" name="precio_venta" value="{{ old('precio_venta', 0) }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Descuento (€)</label><input type="number" class="vx-input" id="descuento" name="descuento" value="{{ old('descuento', 0) }}" step="0.01" min="0" style="font-family:var(--vx-font-mono);"></div>
            <div class="vx-form-group"><label class="vx-label">Precio Final (€) <span class="required">*</span></label><input type="number" class="vx-input" id="precioFinal" name="precio_final" value="{{ old('precio_final', 0) }}" step="0.01" min="0" required style="font-family:var(--vx-font-mono);font-weight:700;background:var(--vx-bg);"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Forma de Pago <span class="required">*</span></label><select class="vx-select" name="forma_pago" required>@foreach(\App\Models\Venta::$formasPago as $k => $v)<option value="{{ $k }}" {{ old('forma_pago') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Venta <span class="required">*</span></label><input type="date" class="vx-input" name="fecha_venta" value="{{ old('fecha_venta', date('Y-m-d')) }}" required></div>
            <div class="vx-form-group"><label class="vx-label">Fecha Entrega</label><input type="date" class="vx-input" name="fecha_entrega" value="{{ old('fecha_entrega') }}"></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:8px;"><a href="{{ route('ventas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar Venta</button></div>
    </form>
</div></div></div>
@push('scripts')
<script>
function calcFinal() { const p = parseFloat(document.getElementById('precioVenta').value) || 0; const d = parseFloat(document.getElementById('descuento').value) || 0; document.getElementById('precioFinal').value = (p - d).toFixed(2); }
document.getElementById('precioVenta').addEventListener('input', calcFinal);
document.getElementById('descuento').addEventListener('input', calcFinal);
</script>
@endpush
@endsection
