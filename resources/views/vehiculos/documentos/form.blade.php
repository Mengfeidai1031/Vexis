@extends('layouts.app')
@section('title', 'Generar ' . $titulo . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Generar {{ $titulo }}</h1>
    <div class="vx-page-actions">
        <a href="{{ route('vehiculos.show', $vehiculo) }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver al vehículo</a>
    </div>
</div>

<div class="vx-alert vx-alert-info" style="margin-bottom:16px;">
    <i class="bi bi-info-circle-fill"></i>
    <span>Al rellenar este formulario se generará un PDF profesional que se guardará automáticamente en la documentación del vehículo <strong>{{ $vehiculo->matricula ?? $vehiculo->chasis }}</strong> ({{ $vehiculo->descripcion_completa }}).</span>
</div>

<div style="max-width: 950px;">
    <form action="{{ route('vehiculos.documentos.generar', ['vehiculo' => $vehiculo, 'tipo' => $tipo]) }}" method="POST">
        @csrf

        {{-- Cabecera resumen del vehículo (solo lectura) --}}
        <div class="vx-card" style="margin-bottom:16px;">
            <div class="vx-card-header"><h4><i class="bi bi-truck" style="color:var(--vx-primary);"></i> Vehículo asociado</h4></div>
            <div class="vx-card-body">
                <div class="vx-form-grid vx-form-grid-3">
                    <div class="vx-form-group"><label class="vx-label">Chasis</label><input type="text" class="vx-input" value="{{ $vehiculo->chasis }}" readonly></div>
                    <div class="vx-form-group"><label class="vx-label">Matrícula</label><input type="text" class="vx-input" value="{{ $vehiculo->matricula ?? 'Sin matricular' }}" readonly></div>
                    <div class="vx-form-group"><label class="vx-label">Marca · Modelo · Versión</label><input type="text" class="vx-input" value="{{ ($vehiculo->marca->nombre ?? '—') }} · {{ $vehiculo->modelo }} {{ $vehiculo->version }}" readonly></div>
                </div>
            </div>
        </div>

        <div class="vx-card">
            <div class="vx-card-header"><h4><i class="bi bi-file-earmark-pdf" style="color:var(--vx-danger);"></i> Datos del documento</h4></div>
            <div class="vx-card-body">

                @if($tipo === 'ficha_tecnica')
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="numero_homologacion">Nº Homologación <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;">
                                <input type="text" class="vx-input @error('numero_homologacion') is-invalid @enderror" id="numero_homologacion" name="numero_homologacion" value="{{ old('numero_homologacion') }}" style="flex:1;font-family:var(--vx-font-mono);" required>
                                <button type="button" class="vx-btn vx-btn-secondary" data-gen-codigo="numero_homologacion" title="Generar automáticamente"><i class="bi bi-magic"></i></button>
                            </div>
                            @error('numero_homologacion')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_emision">Fecha de emisión <span class="required">*</span></label>
                            <input type="date" class="vx-input @error('fecha_emision') is-invalid @enderror" id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="categoria">Categoría <span class="required">*</span></label>
                            <select class="vx-select" id="categoria" name="categoria" required>
                                @foreach(['M1' => 'M1 — Turismo', 'M2' => 'M2 — Autobús ≤5t', 'M3' => 'M3 — Autobús >5t', 'N1' => 'N1 — Ligero', 'N2' => 'N2 — Medio', 'N3' => 'N3 — Pesado'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('categoria') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="combustible">Combustible <span class="required">*</span></label>
                            <select class="vx-select" id="combustible" name="combustible" required>
                                @foreach(['Gasolina', 'Diésel', 'Híbrido', 'Eléctrico', 'GLP'] as $c)
                                    <option value="{{ $c }}" {{ old('combustible') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="transmision">Transmisión <span class="required">*</span></label>
                            <select class="vx-select" id="transmision" name="transmision" required>
                                @foreach(['Manual', 'Automática', 'Semiautomática'] as $t)
                                    <option value="{{ $t }}" {{ old('transmision') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="plazas">Plazas <span class="required">*</span></label>
                            <input type="number" class="vx-input" id="plazas" name="plazas" value="{{ old('plazas', 5) }}" min="1" max="9" required>
                        </div>
                    </div>
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="cilindrada_cc">Cilindrada (cc)</label>
                            <input type="number" class="vx-input" id="cilindrada_cc" name="cilindrada_cc" value="{{ old('cilindrada_cc') }}" min="0" max="10000">
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="potencia_cv">Potencia (CV) <span class="required">*</span></label>
                            <input type="number" class="vx-input" id="potencia_cv" name="potencia_cv" value="{{ old('potencia_cv') }}" min="0" max="2000" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="emisiones_co2">Emisiones CO₂ (g/km)</label>
                            <input type="number" class="vx-input" id="emisiones_co2" name="emisiones_co2" value="{{ old('emisiones_co2') }}" min="0">
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="peso_vacio_kg">Peso vacío (kg)</label>
                            <input type="number" class="vx-input" id="peso_vacio_kg" name="peso_vacio_kg" value="{{ old('peso_vacio_kg') }}" min="0">
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="peso_maximo_kg">Peso máx. autorizado (kg)</label>
                            <input type="number" class="vx-input" id="peso_maximo_kg" name="peso_maximo_kg" value="{{ old('peso_maximo_kg') }}" min="0">
                        </div>
                    </div>

                @elseif($tipo === 'itv')
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="numero_informe">Nº Informe ITV <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;">
                                <input type="text" class="vx-input" id="numero_informe" name="numero_informe" value="{{ old('numero_informe') }}" style="flex:1;font-family:var(--vx-font-mono);" required>
                                <button type="button" class="vx-btn vx-btn-secondary" data-gen-codigo="numero_informe" title="Generar automáticamente"><i class="bi bi-magic"></i></button>
                            </div>
                            @error('numero_informe')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_inspeccion">Fecha inspección <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="fecha_inspeccion" name="fecha_inspeccion" value="{{ old('fecha_inspeccion', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="proxima_revision">Próxima revisión <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="proxima_revision" name="proxima_revision" value="{{ old('proxima_revision', now()->addYear()->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="estacion_itv">Estación ITV <span class="required">*</span></label>
                            <input type="text" class="vx-input" id="estacion_itv" name="estacion_itv" value="{{ old('estacion_itv') }}" maxlength="200" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="kilometraje">Kilometraje <span class="required">*</span></label>
                            <input type="number" class="vx-input" id="kilometraje" name="kilometraje" value="{{ old('kilometraje') }}" min="0" max="9999999" required>
                        </div>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="resultado">Resultado <span class="required">*</span></label>
                        <select class="vx-select" id="resultado" name="resultado" required>
                            @foreach(['favorable' => 'Favorable', 'favorable_con_defectos_leves' => 'Favorable con defectos leves', 'desfavorable' => 'Desfavorable', 'negativa' => 'Negativa'] as $k => $v)
                                <option value="{{ $k }}" {{ old('resultado') == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="defectos">Defectos detectados</label>
                        <textarea class="vx-input" id="defectos" name="defectos" rows="3" maxlength="2000">{{ old('defectos') }}</textarea>
                    </div>

                @elseif($tipo === 'permiso_circulacion')
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="numero_permiso">Nº Permiso <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;">
                                <input type="text" class="vx-input" id="numero_permiso" name="numero_permiso" value="{{ old('numero_permiso') }}" style="flex:1;font-family:var(--vx-font-mono);" required>
                                <button type="button" class="vx-btn vx-btn-secondary" data-gen-codigo="numero_permiso" title="Generar automáticamente"><i class="bi bi-magic"></i></button>
                            </div>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_matriculacion">Fecha matriculación <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="fecha_matriculacion" name="fecha_matriculacion" value="{{ old('fecha_matriculacion') }}" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="jefatura_trafico">Jefatura Tráfico <span class="required">*</span></label>
                            <input type="text" class="vx-input" id="jefatura_trafico" name="jefatura_trafico" value="{{ old('jefatura_trafico', 'Las Palmas de Gran Canaria') }}" maxlength="150" required>
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="cliente_id">Titular (Cliente) <span class="required">*</span></label>
                            <select class="vx-select" id="cliente_id" name="cliente_id" required>
                                <option value="">— Seleccionar titular —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" {{ old('cliente_id', $vehiculo->empresa_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }} · {{ $c->dni }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo cliente</a>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="empresa_id">Empresa responsable <span class="required">*</span></label>
                            <select class="vx-select" id="empresa_id" name="empresa_id" required>
                                @foreach($empresas as $e)
                                    <option value="{{ $e->id }}" {{ old('empresa_id', $vehiculo->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva empresa</a>
                        </div>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="uso">Uso <span class="required">*</span></label>
                        <select class="vx-select" id="uso" name="uso" required>
                            @foreach(['particular' => 'Particular', 'servicio_publico' => 'Servicio público', 'alquiler' => 'Alquiler sin conductor', 'autoescuela' => 'Autoescuela'] as $k => $v)
                                <option value="{{ $k }}" {{ old('uso') == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                @elseif($tipo === 'seguro')
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="aseguradora">Aseguradora <span class="required">*</span></label>
                            <input type="text" class="vx-input" id="aseguradora" name="aseguradora" value="{{ old('aseguradora') }}" maxlength="150" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="numero_poliza">Nº Póliza <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;">
                                <input type="text" class="vx-input" id="numero_poliza" name="numero_poliza" value="{{ old('numero_poliza') }}" style="flex:1;font-family:var(--vx-font-mono);" required>
                                <button type="button" class="vx-btn vx-btn-secondary" data-gen-codigo="numero_poliza" title="Generar automáticamente"><i class="bi bi-magic"></i></button>
                            </div>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="tipo_cobertura">Cobertura <span class="required">*</span></label>
                            <select class="vx-select" id="tipo_cobertura" name="tipo_cobertura" required>
                                @foreach(['terceros' => 'Terceros', 'terceros_ampliado' => 'Terceros ampliado', 'todo_riesgo' => 'Todo Riesgo', 'todo_riesgo_franquicia' => 'Todo Riesgo con franquicia'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('tipo_cobertura') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_inicio">Fecha inicio <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_fin">Fecha fin <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', now()->addYear()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="prima_anual">Prima anual (€) <span class="required">*</span></label>
                            <input type="number" step="0.01" class="vx-input" id="prima_anual" name="prima_anual" value="{{ old('prima_anual') }}" min="0" max="99999.99" required>
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="cliente_id">Tomador (Cliente) <span class="required">*</span></label>
                            <select class="vx-select" id="cliente_id" name="cliente_id" required>
                                <option value="">— Seleccionar tomador —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }} · {{ $c->dni }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo cliente</a>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="franquicia">Franquicia (€)</label>
                            <input type="number" step="0.01" class="vx-input" id="franquicia" name="franquicia" value="{{ old('franquicia') }}" min="0" max="99999.99">
                        </div>
                    </div>

                @elseif($tipo === 'contrato')
                    <div class="vx-form-grid vx-form-grid-3">
                        <div class="vx-form-group">
                            <label class="vx-label" for="numero_contrato">Nº Contrato <span class="required">*</span></label>
                            <div style="display:flex;gap:6px;">
                                <input type="text" class="vx-input" id="numero_contrato" name="numero_contrato" value="{{ old('numero_contrato') }}" style="flex:1;font-family:var(--vx-font-mono);" required>
                                <button type="button" class="vx-btn vx-btn-secondary" data-gen-codigo="numero_contrato" title="Generar automáticamente"><i class="bi bi-magic"></i></button>
                            </div>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="tipo_contrato">Tipo <span class="required">*</span></label>
                            <select class="vx-select" id="tipo_contrato" name="tipo_contrato" required>
                                @foreach(['compraventa' => 'Compraventa', 'deposito' => 'Depósito', 'alquiler' => 'Alquiler', 'custodia' => 'Custodia', 'cesion' => 'Cesión'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('tipo_contrato') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="fecha_contrato">Fecha contrato <span class="required">*</span></label>
                            <input type="date" class="vx-input" id="fecha_contrato" name="fecha_contrato" value="{{ old('fecha_contrato', now()->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="cliente_id">Contraparte (Cliente) <span class="required">*</span></label>
                            <select class="vx-select" id="cliente_id" name="cliente_id" required>
                                <option value="">— Seleccionar cliente —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }} {{ $c->apellidos }} · {{ $c->dni }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('clientes.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nuevo cliente</a>
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="empresa_id">Empresa <span class="required">*</span></label>
                            <select class="vx-select" id="empresa_id" name="empresa_id" required>
                                @foreach($empresas as $e)
                                    <option value="{{ $e->id }}" {{ old('empresa_id', $vehiculo->empresa_id) == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('empresas.create') }}" class="vx-select-create" target="_blank"><i class="bi bi-plus-circle"></i> Crear nueva empresa</a>
                        </div>
                    </div>
                    <div class="vx-form-grid">
                        <div class="vx-form-group">
                            <label class="vx-label" for="importe">Importe (€)</label>
                            <input type="number" step="0.01" class="vx-input" id="importe" name="importe" value="{{ old('importe') }}" min="0" max="9999999.99">
                        </div>
                        <div class="vx-form-group">
                            <label class="vx-label" for="duracion_meses">Duración (meses)</label>
                            <input type="number" class="vx-input" id="duracion_meses" name="duracion_meses" value="{{ old('duracion_meses') }}" min="0" max="600">
                        </div>
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="clausulas_adicionales">Cláusulas adicionales</label>
                        <textarea class="vx-input" id="clausulas_adicionales" name="clausulas_adicionales" rows="4" maxlength="3000">{{ old('clausulas_adicionales') }}</textarea>
                    </div>
                @endif

                <div class="vx-form-grid">
                    <div class="vx-form-group">
                        <label class="vx-label" for="fecha_vencimiento">Vencimiento del documento</label>
                        <input type="date" class="vx-input" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}">
                    </div>
                    <div class="vx-form-group">
                        <label class="vx-label" for="observaciones">Observaciones</label>
                        <input type="text" class="vx-input" id="observaciones" name="observaciones" value="{{ old('observaciones') }}" maxlength="1000">
                    </div>
                </div>

                <input type="hidden" name="solo_pdf" id="solo_pdf" value="0">
                <div style="display:flex;justify-content:space-between;gap:8px;margin-top:16px;flex-wrap:wrap;">
                    <a href="{{ route('vehiculos.documentos.hub') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver al hub</a>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <button type="submit" class="vx-btn vx-btn-secondary" data-submit-mode="1" title="Descarga el PDF sin guardarlo en la ficha del vehículo"><i class="bi bi-file-earmark-pdf"></i> Generar PDF</button>
                        <button type="submit" class="vx-btn vx-btn-primary" data-submit-mode="0" title="Genera el PDF y lo añade automáticamente a la documentación del vehículo"><i class="bi bi-cloud-upload"></i> Generar PDF y subir</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-submit-mode]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('solo_pdf').value = this.dataset.submitMode;
    });
});
document.querySelectorAll('[data-gen-codigo]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.genCodigo);
        if (!input) return;
        const url = '{{ route("vehiculos.documentos.generarCodigo", ["tipo" => $tipo]) }}';
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        fetch(url, { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
            .then(r => r.json())
            .then(d => { input.value = d.codigo; })
            .catch(() => alert('Error al generar código'))
            .finally(() => { btn.disabled = false; btn.innerHTML = original; });
    });
});
</script>
@endpush
@endsection
