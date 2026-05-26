@extends('layouts.app')
@section('title', 'Nuevo Coche Sustitución - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar Coche de Sustitución</h1><a href="{{ route('coches-sustitucion.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:720px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('coches-sustitucion.store') }}" method="POST">@csrf
        <h3 style="margin:0 0 12px;font-size:14px;color:var(--vx-primary);"><i class="bi bi-car-front"></i> Datos del vehículo</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Matrícula <span class="required">*</span></label><input type="text" class="vx-input @error('matricula') is-invalid @enderror" name="matricula" value="{{ old('matricula') }}" required style="font-family:var(--vx-font-mono);text-transform:uppercase;">@error('matricula')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="vx-form-group"><label class="vx-label">Modelo <span class="required">*</span></label><input type="text" class="vx-input" name="modelo" value="{{ old('modelo') }}" required></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Marca <span class="required">*</span></label><select class="vx-select" name="marca_id" required>@foreach($marcas as $m)<option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach</select><a href="{{ route('gestion.marcas') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Gestionar marcas</a></div>
            <div class="vx-form-group"><label class="vx-label">Color</label><input type="text" class="vx-input" name="color" value="{{ old('color') }}"></div>
            <div class="vx-form-group"><label class="vx-label">Año</label><input type="number" class="vx-input" name="anio" value="{{ old('anio', date('Y')) }}" min="2000" max="2030"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
            <div class="vx-form-group"><label class="vx-label">Taller <span class="required">*</span></label><select class="vx-select" name="taller_id" required><option value="">Seleccionar...</option>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ old('taller_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select><a href="{{ route('talleres.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
            <div class="vx-form-group"><label class="vx-label">Empresa <span class="required">*</span></label><select class="vx-select" name="empresa_id" required>@foreach($empresas as $e)<option value="{{ $e->id }}" {{ old('empresa_id') == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>@endforeach</select><a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a></div>
        </div>
        <div class="vx-form-group"><label class="vx-label">Observaciones</label><textarea class="vx-input" name="observaciones" rows="2">{{ old('observaciones') }}</textarea></div>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--vx-border);">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" id="reservar" name="reservar" value="1" {{ old('reservar') ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--vx-primary);">
                <i class="bi bi-calendar-check" style="color:var(--vx-primary);"></i> Reservar este coche al crearlo
            </label>
        </div>

        <div id="bloqueReserva" style="display:{{ old('reservar') ? 'block' : 'none' }};margin-top:16px;padding:16px;background:var(--vx-surface);border:1px dashed var(--vx-border);border-radius:8px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                <div class="vx-form-group">
                    <label class="vx-label">Cliente registrado</label>
                    <select class="vx-select" name="cliente_id">
                        <option value="">— No registrado —</option>
                        @foreach($clientes as $c)<option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }}</option>@endforeach
                    </select>
                    <a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo</a>
                    @error('cliente_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">o Nombre del cliente</label>
                    <input type="text" class="vx-input" name="cliente_nombre" value="{{ old('cliente_nombre') }}" placeholder="Si no está registrado">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                <div class="vx-form-group"><label class="vx-label">Fecha inicio <span class="required">*</span></label><input type="date" class="vx-input @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}">@error('fecha_inicio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="vx-form-group"><label class="vx-label">Fecha fin <span class="required">*</span></label><input type="date" class="vx-input @error('fecha_fin') is-invalid @enderror" name="fecha_fin" value="{{ old('fecha_fin') }}">@error('fecha_fin')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="vx-form-group">
                    <label class="vx-label">Estado</label>
                    <select class="vx-select" name="estado_reserva">
                        @foreach(\App\Models\ReservaSustitucion::$estados as $k => $v)<option value="{{ $k }}" {{ old('estado_reserva', 'reservado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="vx-form-group"><label class="vx-label">Observaciones de la reserva</label><textarea class="vx-input" name="observaciones_reserva" rows="2">{{ old('observaciones_reserva') }}</textarea></div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;"><a href="{{ route('coches-sustitucion.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a><button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Registrar</button></div>
    </form>
</div></div></div>

@push('scripts')
<script>
document.getElementById('reservar').addEventListener('change', function(){
    document.getElementById('bloqueReserva').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
@endsection
