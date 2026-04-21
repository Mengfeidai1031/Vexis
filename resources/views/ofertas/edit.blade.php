@extends('layouts.app')
@section('title', 'Editar Oferta #' . $oferta->id . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Editar Oferta #{{ $oferta->id }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('ofertas.show', $oferta) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div class="vx-alert vx-alert-info" style="margin-bottom:16px;">
    <i class="bi bi-info-circle-fill"></i>
    <span>El PDF original se conserva como evidencia. Aquí puedes corregir asociaciones e importes manualmente.</span>
</div>

<div style="max-width: 950px;">
    <form action="{{ route('ofertas.update', $oferta) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="vx-card">
            <div class="vx-card-header"><h4><i class="bi bi-pencil-square" style="color: var(--vx-primary);"></i> Datos de la oferta</h4></div>
            <div class="vx-card-body">
                <div class="vx-form-grid vx-form-grid-3">
                    <div class="vx-form-group">
                        <label class="vx-label" for="fecha">Fecha <span class="required">*</span></label>
                        <input type="date" class="vx-input @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $oferta->fecha?->format('Y-m-d')) }}" required>
                        @error('fecha')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="cliente_id">Cliente</label>
                        <select class="vx-select @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                            <option value="">— Sin cliente asociado —</option>
                            @foreach($clientes as $c)
                                <option value="{{ $c->id }}" {{ old('cliente_id', $oferta->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }} · {{ $c->dni }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="vehiculo_id">Vehículo</label>
                        <select class="vx-select @error('vehiculo_id') is-invalid @enderror" id="vehiculo_id" name="vehiculo_id">
                            <option value="">— Sin vehículo asociado —</option>
                            @foreach($vehiculos as $v)
                                <option value="{{ $v->id }}" {{ old('vehiculo_id', $oferta->vehiculo_id) == $v->id ? 'selected' : '' }}>{{ $v->chasis }} · {{ $v->modelo }} {{ $v->version }}</option>
                            @endforeach
                        </select>
                        @error('vehiculo_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="vx-form-grid">
                    <div class="vx-form-group">
                        <label class="vx-label" for="base_imponible">Base Imponible (€)</label>
                        <input type="number" step="0.01" min="0" class="vx-input" id="base_imponible" name="base_imponible" value="{{ old('base_imponible', $oferta->base_imponible) }}">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="impuestos">Impuestos (€)</label>
                        <input type="number" step="0.01" min="0" class="vx-input" id="impuestos" name="impuestos" value="{{ old('impuestos', $oferta->impuestos) }}">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="total_sin_impuestos">Total sin impuestos (€)</label>
                        <input type="number" step="0.01" min="0" class="vx-input" id="total_sin_impuestos" name="total_sin_impuestos" value="{{ old('total_sin_impuestos', $oferta->total_sin_impuestos) }}">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="total_con_impuestos">Total con impuestos (€)</label>
                        <input type="number" step="0.01" min="0" class="vx-input" id="total_con_impuestos" name="total_con_impuestos" value="{{ old('total_con_impuestos', $oferta->total_con_impuestos) }}">
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
                    <a href="{{ route('ofertas.show', $oferta) }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
