@extends('layouts.app')
@section('title', 'Nuevo Registro Verifactu - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Registrar en Verifactu</h1><a href="{{ route('verifactu.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
<div style="max-width:600px;"><div class="vx-card"><div class="vx-card-body">
    <form action="{{ route('verifactu.registrar') }}" method="POST">@csrf
        <div class="vx-form-group">
            <label class="vx-label">Factura <span class="required">*</span></label>
            <select class="vx-select" name="factura_id" required>
                <option value="">Seleccionar factura...</option>
                @foreach($facturas as $f)
                <option value="{{ $f->id }}">{{ $f->codigo_factura }} — {{ $f->cliente ? $f->cliente->nombre . ' ' . $f->cliente->apellidos : 'Sin cliente' }} — {{ number_format($f->total, 2) }}€</option>
                @endforeach
            </select>
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Tipo de Operación <span class="required">*</span></label>
            <select class="vx-select" name="tipo_operacion" required>
                @foreach(\App\Models\Verifactu::$tiposOperacion as $k => $v)
                <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="vx-form-group">
            <label class="vx-label">Observaciones</label>
            <textarea class="vx-input" name="observaciones" rows="2" placeholder="Notas adicionales..."></textarea>
        </div>
        <div style="background:var(--vx-warning-bg);padding:12px;border-radius:8px;margin-bottom:12px;">
            <p style="font-size:11px;color:var(--vx-warning);margin:0;"><i class="bi bi-exclamation-triangle"></i> <strong>Registro manual.</strong> Los registros Verifactu se generan automáticamente al crear una factura. Use este formulario solo para registrar manualmente facturas existentes que no fueron registradas automáticamente.</p>
        </div>
        <div style="background:var(--vx-bg);padding:12px;border-radius:8px;margin-bottom:16px;">
            <p style="font-size:11px;color:var(--vx-text-muted);margin:0;"><i class="bi bi-info-circle"></i> El hash SHA-256 se generará automáticamente encadenado al último registro existente, garantizando la integridad de la cadena Verifactu conforme al RD 1007/2023 art. 12.</p>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="{{ route('verifactu.index') }}" class="vx-btn vx-btn-secondary">Cancelar</a>
            <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-shield-check"></i> Registrar</button>
        </div>
    </form>
</div></div></div>
@endsection
