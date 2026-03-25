@extends('layouts.app')
@section('title', 'Tasación Formal - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title"><i class="bi bi-clipboard-check" style="color:#F1C40F;"></i> Solicitud de Tasación Formal</h1>
    <a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="vx-card" style="margin-bottom:16px;background:rgba(52,152,219,0.06);border-color:rgba(52,152,219,0.22);">
    <div class="vx-card-body" style="padding:12px 16px;display:flex;align-items:flex-start;gap:8px;font-size:12px;color:var(--vx-text-muted);">
        <i class="bi bi-info-circle" style="color:var(--vx-info);font-size:16px;flex-shrink:0;"></i>
        Esta solicitud se envía al módulo de Tasaciones para revisión. Puedes usar primero la <strong>Pretasación IA</strong> y después tramitar aquí la tasación oficial.
    </div>
</div>

<div style="display:grid;grid-template-columns:1.2fr 1fr;gap:16px;">
    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-send"></i> Nueva solicitud</h4></div>
        <div class="vx-card-body">
            <form action="{{ route('cliente.tasacion.store') }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Marca vehículo <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('vehiculo_marca') is-invalid @enderror" name="vehiculo_marca" value="{{ old('vehiculo_marca') }}" required>
                        @error('vehiculo_marca')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Modelo <span class="required">*</span></label>
                        <input type="text" class="vx-input @error('vehiculo_modelo') is-invalid @enderror" name="vehiculo_modelo" value="{{ old('vehiculo_modelo') }}" required>
                        @error('vehiculo_modelo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Año <span class="required">*</span></label>
                        <input type="number" class="vx-input @error('vehiculo_anio') is-invalid @enderror" name="vehiculo_anio" value="{{ old('vehiculo_anio', date('Y') - 3) }}" min="1990" max="2030" required>
                        @error('vehiculo_anio')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Kilometraje <span class="required">*</span></label>
                        <input type="number" class="vx-input @error('kilometraje') is-invalid @enderror" name="kilometraje" value="{{ old('kilometraje') }}" min="0" required style="font-family:var(--vx-font-mono);">
                        @error('kilometraje')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Matrícula</label>
                        <input type="text" class="vx-input @error('matricula') is-invalid @enderror" name="matricula" value="{{ old('matricula') }}" style="text-transform:uppercase;">
                        @error('matricula')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Combustible</label>
                        <select class="vx-select @error('combustible') is-invalid @enderror" name="combustible">
                            <option value="">—</option>
                            @foreach(\App\Models\Tasacion::$combustibles as $combustible)
                                <option value="{{ $combustible }}" {{ old('combustible') === $combustible ? 'selected' : '' }}>{{ $combustible }}</option>
                            @endforeach
                        </select>
                        @error('combustible')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Estado del vehículo <span class="required">*</span></label>
                        <select class="vx-select @error('estado_vehiculo') is-invalid @enderror" name="estado_vehiculo" required>
                            @foreach(\App\Models\Tasacion::$estadosVehiculo as $key => $label)
                                <option value="{{ $key }}" {{ old('estado_vehiculo', 'bueno') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('estado_vehiculo')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Concesionario <span class="required">*</span></label>
                        <select class="vx-select @error('empresa_id') is-invalid @enderror" name="empresa_id" required>
                            <option value="">Seleccione…</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ (string) old('empresa_id') === (string) $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                        @error('empresa_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 12px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Marca comercial (opcional)</label>
                        <select class="vx-select @error('marca_id') is-invalid @enderror" name="marca_id">
                            <option value="">—</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}" {{ (string) old('marca_id') === (string) $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                        @error('marca_id')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Observaciones</label>
                        <textarea class="vx-input @error('observaciones') is-invalid @enderror" name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
                        @error('observaciones')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:8px;">
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-check-lg"></i> Enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>

    <div class="vx-card">
        <div class="vx-card-header"><h4><i class="bi bi-clock-history"></i> Mis solicitudes recientes</h4></div>
        <div class="vx-card-body">
            @if($solicitudes->count() > 0)
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach($solicitudes as $s)
                        <div style="border:1px solid var(--vx-border);border-radius:8px;padding:10px;">
                            <div style="display:flex;justify-content:space-between;gap:8px;align-items:center;">
                                <strong style="font-size:12px;">{{ $s->codigo_tasacion }}</strong>
                                <span class="vx-badge vx-badge-{{ match($s->estado) { 'pendiente' => 'warning', 'valorada' => 'info', 'aceptada' => 'success', default => 'danger' } }}">{{ \App\Models\Tasacion::$estados[$s->estado] ?? ucfirst($s->estado) }}</span>
                            </div>
                            <div style="font-size:12px;margin-top:4px;">{{ $s->vehiculo_marca }} {{ $s->vehiculo_modelo }} ({{ $s->vehiculo_anio }})</div>
                            <div style="font-size:11px;color:var(--vx-text-muted);margin-top:2px;">Solicitada el {{ $s->fecha_tasacion->format('d/m/Y') }}</div>
                            @if($s->valor_final)
                                <div style="font-size:12px;color:var(--vx-success);font-weight:700;margin-top:4px;">Valor final: {{ number_format($s->valor_final, 2) }} €</div>
                            @elseif($s->valor_estimado)
                                <div style="font-size:12px;color:var(--vx-primary);font-weight:700;margin-top:4px;">Valor estimado: {{ number_format($s->valor_estimado, 2) }} €</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="vx-empty" style="padding:28px 10px;">
                    <i class="bi bi-clipboard-x"></i>
                    <p>Aún no tienes solicitudes de tasación formal.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
