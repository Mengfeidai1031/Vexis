@extends('layouts.app')

@section('title', 'Cargar Oferta Comercial')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Cargar Nueva Oferta Comercial (PDF)</h2>
            <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<!-- Mensajes de error -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- Información importante -->
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Procesamiento Automático</h5>
            <p>El sistema procesará automáticamente el PDF y extraerá según la marca seleccionada:</p>
            <ul class="mb-0">
                <li><strong>Datos del cliente</strong> (nombre, dirección, teléfono, email, DNI)</li>
                <li><strong>Datos de la empresa</strong> (nombre, CIF, dirección, teléfono)</li>
                <li><strong>Datos del vehículo</strong> (si existe número de bastidor/chasis)</li>
                <li><strong>Líneas de detalle</strong> (opciones, descuentos, accesorios)</li>
            </ul>
            <hr>
            <p class="mb-0"><strong>Nota:</strong> Si no existe número de bastidor, se creará la oferta sin vehículo asociado (documento informativo).</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ofertas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Selector de Marca -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Seleccione la Marca del Vehículo <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check card h-100 @error('marca') border-danger @enderror">
                                    <div class="card-body text-center p-4">
                                        <input class="form-check-input visually-hidden" type="radio" name="marca" id="marca_nissan" value="nissan" required {{ old('marca') == 'nissan' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 cursor-pointer" for="marca_nissan" style="cursor: pointer;">
                                            <div class="marca-icon mb-2">
                                                <svg width="80" height="80" viewBox="0 0 100 100">
                                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#C3002F" stroke-width="4"/>
                                                    <rect x="10" y="45" width="80" height="10" fill="#C3002F"/>
                                                    <text x="50" y="75" font-size="12" text-anchor="middle" fill="#C3002F" font-weight="bold">NISSAN</text>
                                                </svg>
                                            </div>
                                            <h5 class="mb-0">NISSAN</h5>
                                            <small class="text-muted">PDFs de concesionarios Nissan</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check card h-100 @error('marca') border-danger @enderror">
                                    <div class="card-body text-center p-4">
                                        <input class="form-check-input visually-hidden" type="radio" name="marca" id="marca_renault" value="renault_dacia" required {{ old('marca') == 'renault_dacia' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="marca_renault" style="cursor: pointer;">
                                            <div class="marca-icon mb-2">
                                                <svg width="80" height="80" viewBox="0 0 100 100">
                                                    <polygon points="50,5 95,50 50,95 5,50" fill="none" stroke="#FFCC00" stroke-width="4"/>
                                                    <text x="50" y="45" font-size="9" text-anchor="middle" fill="#FFCC00" font-weight="bold">RENAULT</text>
                                                    <text x="50" y="60" font-size="9" text-anchor="middle" fill="#646B52" font-weight="bold">DACIA</text>
                                                </svg>
                                            </div>
                                            <h5 class="mb-0">RENAULT / DACIA</h5>
                                            <small class="text-muted">PDFs de concesionarios Renault o Dacia</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('marca')
                            <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <!-- Archivo PDF -->
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label fw-bold">Archivo PDF de la Oferta <span class="text-danger">*</span></label>
                        <input 
                            type="file" 
                            class="form-control @error('pdf_file') is-invalid @enderror" 
                            id="pdf_file" 
                            name="pdf_file" 
                            accept="application/pdf"
                            required
                        >
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tamaño máximo: 10MB. Solo archivos PDF.</small>
                    </div>

                    <!-- Vista previa del nombre del archivo -->
                    <div id="file-info" class="mb-3 d-none">
                        <div class="alert alert-secondary">
                            <strong>Archivo seleccionado:</strong> <span id="file-name"></span><br>
                            <strong>Tamaño:</strong> <span id="file-size"></span>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Procesar PDF y Guardar Oferta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check.card {
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
    }
    .form-check.card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
    }
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
    }
    .form-check-input:checked + .form-check-label .card-body {
        background-color: #e7f1ff;
    }
    input[type="radio"]:checked ~ .form-check-label {
        color: #0d6efd;
    }
    .form-check:has(input:checked) {
        border-color: #0d6efd !important;
        background-color: #e7f1ff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('pdf_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('file-size').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('file-info').classList.remove('d-none');
        } else {
            document.getElementById('file-info').classList.add('d-none');
        }
    });

    // Resaltar selección de marca
    document.querySelectorAll('input[name="marca"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.form-check.card').forEach(card => {
                card.style.borderColor = '#dee2e6';
                card.style.backgroundColor = '';
            });
            if (this.checked) {
                this.closest('.form-check.card').style.borderColor = '#0d6efd';
                this.closest('.form-check.card').style.backgroundColor = '#e7f1ff';
            }
        });
    });
</script>
@endpush