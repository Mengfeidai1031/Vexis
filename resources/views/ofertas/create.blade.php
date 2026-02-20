@extends('layouts.app')
@section('title', 'Cargar Oferta - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">Cargar Nueva Oferta (PDF)</h1>
    <a href="{{ route('ofertas.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div style="max-width: 750px;">
    <div class="vx-alert vx-alert-info" style="margin-bottom: 16px;">
        <i class="bi bi-info-circle-fill"></i>
        <div>
            <strong>Procesamiento Automático</strong> — El sistema extraerá datos del cliente, empresa, vehículo y líneas de detalle. Si no hay número de bastidor, se creará como documento informativo.
        </div>
    </div>

    <div class="vx-card">
        <div class="vx-card-body">
            <form action="{{ route('ofertas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Selector de Marca --}}
                <div class="vx-form-group">
                    <label class="vx-label">Marca del Vehículo <span class="required">*</span></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <label class="vx-marca-card" id="card-nissan">
                            <input type="radio" name="marca" value="nissan" required {{ old('marca') == 'nissan' ? 'checked' : '' }} style="display: none;">
                            <div class="vx-marca-inner">
                                <svg width="60" height="60" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#C3002F" stroke-width="4"/>
                                    <rect x="12" y="45" width="76" height="10" fill="#C3002F"/>
                                    <text x="50" y="72" font-size="11" text-anchor="middle" fill="#C3002F" font-weight="bold">NISSAN</text>
                                </svg>
                                <div style="font-weight: 700; font-size: 14px;">NISSAN</div>
                                <div style="font-size: 11px; color: var(--vx-text-muted);">Concesionarios Nissan</div>
                            </div>
                        </label>
                        <label class="vx-marca-card" id="card-renault">
                            <input type="radio" name="marca" value="renault_dacia" required {{ old('marca') == 'renault_dacia' ? 'checked' : '' }} style="display: none;">
                            <div class="vx-marca-inner">
                                <svg width="60" height="60" viewBox="0 0 100 100">
                                    <polygon points="50,8 92,50 50,92 8,50" fill="none" stroke="#FFCC00" stroke-width="4"/>
                                    <text x="50" y="45" font-size="9" text-anchor="middle" fill="#FFCC00" font-weight="bold">RENAULT</text>
                                    <text x="50" y="60" font-size="9" text-anchor="middle" fill="#646B52" font-weight="bold">DACIA</text>
                                </svg>
                                <div style="font-weight: 700; font-size: 14px;">RENAULT / DACIA</div>
                                <div style="font-size: 11px; color: var(--vx-text-muted);">Concesionarios Renault o Dacia</div>
                            </div>
                        </label>
                    </div>
                    @error('marca')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Archivo PDF --}}
                <div class="vx-form-group">
                    <label class="vx-label" for="pdf_file">Archivo PDF <span class="required">*</span></label>
                    <input type="file" class="vx-input @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept="application/pdf" required>
                    @error('pdf_file')<div class="vx-invalid-feedback">{{ $message }}</div>@enderror
                    <div class="vx-form-hint">Máximo 10MB. Solo PDF.</div>
                </div>

                <div id="file-info" style="display: none; margin-bottom: 16px;">
                    <div class="vx-alert vx-alert-gray">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span><strong id="file-name"></strong> — <span id="file-size"></span></span>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <a href="{{ route('ofertas.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
                    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-upload"></i> Procesar y Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.vx-marca-card{display:block;cursor:pointer;border:2px solid var(--vx-border);border-radius:var(--vx-radius);transition:all .2s;}
.vx-marca-card:hover{border-color:var(--vx-primary);box-shadow:0 0 0 3px rgba(51,170,221,.12);}
.vx-marca-card:has(input:checked){border-color:var(--vx-primary);background:rgba(51,170,221,.06);}
.vx-marca-inner{padding:20px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:6px;}
</style>
@endsection

@push('scripts')
<script>
    document.getElementById('pdf_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('file-size').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('file-info').style.display = '';
        } else {
            document.getElementById('file-info').style.display = 'none';
        }
    });
</script>
@endpush
