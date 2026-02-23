@extends('layouts.app')
@section('title', $taller->nombre . ' - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $taller->nombre }}</h1>
    <div class="vx-page-actions">@can('editar talleres')<a href="{{ route('talleres.edit', $taller) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan <a href="{{ route('talleres.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:900px;">
    <div class="vx-card"><div class="vx-card-header"><h4>Información</h4></div><div class="vx-card-body">
        <div class="vx-info-row"><div class="vx-info-label">Código</div><div class="vx-info-value" style="font-family:var(--vx-font-mono);">{{ $taller->codigo }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Marca</div><div class="vx-info-value">@if($taller->marca)<span class="vx-badge" style="background:{{ $taller->marca->color }}20;color:{{ $taller->marca->color }};">{{ $taller->marca->nombre }}</span>@else — @endif</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Domicilio</div><div class="vx-info-value">{{ $taller->domicilio }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Localidad</div><div class="vx-info-value">{{ $taller->localidad ?? '—' }} ({{ $taller->isla ?? '—' }})</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Teléfono</div><div class="vx-info-value">{{ $taller->telefono ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Empresa</div><div class="vx-info-value">{{ $taller->empresa->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Centro</div><div class="vx-info-value">{{ $taller->centro->nombre ?? '—' }}</div></div>
        <div class="vx-info-row"><div class="vx-info-label">Capacidad diaria</div><div class="vx-info-value"><span class="vx-badge vx-badge-primary">{{ $taller->capacidad_diaria }} citas</span></div></div>
    </div></div>
    <div>
        <div class="vx-card" style="margin-bottom:16px;"><div class="vx-card-header"><h4>Estadísticas</h4></div><div class="vx-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div style="text-align:center;padding:12px;background:var(--vx-bg);border-radius:8px;"><div style="font-size:28px;font-weight:800;color:var(--vx-success);">{{ $taller->mecanicos->count() }}</div><div style="font-size:11px;color:var(--vx-text-muted);">Mecánicos</div></div>
            <div style="text-align:center;padding:12px;background:var(--vx-bg);border-radius:8px;"><div style="font-size:28px;font-weight:800;color:var(--vx-info);">{{ $taller->citas_count }}</div><div style="font-size:11px;color:var(--vx-text-muted);">Citas</div></div>
            <div style="text-align:center;padding:12px;background:var(--vx-bg);border-radius:8px;grid-column:span 2;"><div style="font-size:28px;font-weight:800;color:var(--vx-warning);">{{ $taller->coches_sustitucion_count }}</div><div style="font-size:11px;color:var(--vx-text-muted);">Coches sustitución</div></div>
        </div></div>
        @if($taller->mecanicos->count() > 0)
        <div class="vx-card"><div class="vx-card-header"><h4>Mecánicos</h4></div><div class="vx-card-body" style="padding:0;">
            <table class="vx-table"><thead><tr><th>Nombre</th><th>Especialidad</th></tr></thead><tbody>
            @foreach($taller->mecanicos as $m)<tr><td style="font-weight:600;">{{ $m->nombre_completo }}</td><td style="font-size:12px;">{{ $m->especialidad ?? '—' }}</td></tr>@endforeach
            </tbody></table>
        </div></div>
        @endif
    </div>
</div>
@endsection
