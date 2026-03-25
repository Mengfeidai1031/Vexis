@extends('layouts.app')
@section('title', 'Solicitar Tasación - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-clipboard-check" style="color:var(--vx-success);"></i> Solicitar Tasación</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px;">
    <div class="vx-card" style="grid-column:span 2;">
        <div class="vx-card-body">
            <div style="display:flex;gap:12px;align-items:center;margin-bottom:16px;">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#2ECC71,#27AE60);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;"><i class="bi bi-clipboard-check"></i></div>
                <div>
                    <h3 style="margin:0;font-size:16px;font-weight:800;">Tasación Formal</h3>
                    <p style="margin:0;font-size:12px;color:var(--vx-text-muted);">Completa el formulario y nuestro equipo te contactará con una valoración precisa.</p>
                </div>
            </div>
            <form action="{{ route('cliente.tasacion.store') }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Marca del vehículo <span class="required">*</span></label>
                        <select class="vx-select" name="vehiculo_marca" required>
                            <option value="">Seleccionar...</option>
                            @foreach($marcas as $m)<option value="{{ $m->nombre }}" {{ old('vehiculo_marca') == $m->nombre ? 'selected' : '' }}>{{ $m->nombre }}</option>@endforeach
                            <option value="Otra" {{ old('vehiculo_marca') == 'Otra' ? 'selected' : '' }}>Otra marca</option>
                        </select>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Modelo <span class="required">*</span></label>
                        <input type="text" class="vx-input" name="vehiculo_modelo" value="{{ old('vehiculo_modelo') }}" required placeholder="Ej: Qashqai, Clio, Duster...">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Año <span class="required">*</span></label>
                        <input type="number" class="vx-input" name="vehiculo_anio" value="{{ old('vehiculo_anio') }}" required min="1990" max="2030" placeholder="2020">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Kilometraje <span class="required">*</span></label>
                        <input type="number" class="vx-input" name="kilometraje" value="{{ old('kilometraje') }}" required min="0" placeholder="65000">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Matrícula</label>
                        <input type="text" class="vx-input" name="matricula" value="{{ old('matricula') }}" placeholder="1234 ABC">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                    <div class="vx-form-group">
                        <label class="vx-label">Combustible</label>
                        <select class="vx-select" name="combustible">
                            <option value="">—</option>
                            <option value="Gasolina" {{ old('combustible') == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                            <option value="Diésel" {{ old('combustible') == 'Diésel' ? 'selected' : '' }}>Diésel</option>
                            <option value="Híbrido" {{ old('combustible') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                            <option value="Eléctrico" {{ old('combustible') == 'Eléctrico' ? 'selected' : '' }}>Eléctrico</option>
                            <option value="GLP" {{ old('combustible') == 'GLP' ? 'selected' : '' }}>GLP</option>
                        </select>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label">Estado general</label>
                        <select class="vx-select" name="estado_vehiculo">
                            <option value="">—</option>
                            <option value="excelente" {{ old('estado_vehiculo') == 'excelente' ? 'selected' : '' }}>Excelente</option>
                            <option value="bueno" {{ old('estado_vehiculo') == 'bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="regular" {{ old('estado_vehiculo') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="malo" {{ old('estado_vehiculo') == 'malo' ? 'selected' : '' }}>Necesita reparaciones</option>
                        </select>
                    </div>
                </div>
                <div class="vx-form-group">
                    <label class="vx-label">Observaciones</label>
                    <textarea class="vx-input" name="observaciones" rows="3" placeholder="Detalles adicionales: extras, estado de la carrocería, historial de mantenimiento...">{{ old('observaciones') }}</textarea>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:8px;">
                    <a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-send"></i> Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="vx-card" style="max-width:900px;margin-top:16px;">
    <div class="vx-card-body" style="display:flex;gap:16px;align-items:center;">
        <i class="bi bi-lightbulb" style="font-size:24px;color:var(--vx-warning);flex-shrink:0;"></i>
        <div>
            <p style="margin:0;font-size:13px;color:var(--vx-text-muted);">
                <strong>¿Quieres una estimación rápida?</strong> Usa nuestra <a href="{{ route('cliente.pretasacion') }}" style="color:var(--vx-primary);">pretasación con IA</a> para obtener un rango de precios orientativo al instante. La tasación formal es realizada por nuestros expertos para obtener un valor preciso.
            </p>
        </div>
    </div>
</div>
@endsection
