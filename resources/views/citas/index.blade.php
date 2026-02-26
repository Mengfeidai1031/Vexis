@extends('layouts.app')
@section('title', 'Citas Taller - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title">Citas de Taller</h1><div class="vx-page-actions">@can('crear citas')<a href="{{ route('citas.create') }}" class="vx-btn vx-btn-primary"><i class="bi bi-plus-circle"></i> Nueva Cita</a>@endcan</div></div>

{{-- Calendario semanal --}}
<div class="vx-card" style="margin-bottom:20px;">
    <div class="vx-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h4><i class="bi bi-calendar-week" style="color:var(--vx-primary);"></i> Semana {{ $semanaInicio->format('d/m') }} — {{ $semanaFin->format('d/m/Y') }}</h4>
        <div style="display:flex;gap:6px;">
            <a href="{{ route('citas.index', ['semana' => $semanaInicio->copy()->subWeek()->format('Y-m-d')]) }}" class="vx-btn vx-btn-secondary vx-btn-sm"><i class="bi bi-chevron-left"></i></a>
            <a href="{{ route('citas.index', ['semana' => now()->format('Y-m-d')]) }}" class="vx-btn vx-btn-secondary vx-btn-sm">Hoy</a>
            <a href="{{ route('citas.index', ['semana' => $semanaInicio->copy()->addWeek()->format('Y-m-d')]) }}" class="vx-btn vx-btn-secondary vx-btn-sm"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>
    <div class="vx-card-body" style="padding:0;overflow-x:auto;">
        <table class="vx-table" style="min-width:700px;">
            <thead><tr><th style="width:60px;">Hora</th>
                @for($d = 0; $d < 7; $d++)
                @php $dia = $semanaInicio->copy()->addDays($d); $hoy = $dia->isToday(); @endphp
                <th style="text-align:center;{{ $hoy ? 'background:var(--vx-primary);color:white;' : '' }}">{{ $dia->translatedFormat('D d') }}</th>
                @endfor
            </tr></thead>
            <tbody>
                @for($h = 8; $h <= 18; $h++)
                <tr>
                    <td style="font-size:11px;font-family:var(--vx-font-mono);color:var(--vx-text-muted);">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</td>
                    @for($d = 0; $d < 7; $d++)
                    @php
                        $dia = $semanaInicio->copy()->addDays($d)->format('Y-m-d');
                        $hora = str_pad($h, 2, '0', STR_PAD_LEFT);
                        $citasSlot = ($citasSemana[$dia] ?? collect())->filter(fn($c) => substr($c->hora_inicio, 0, 2) == $hora);
                    @endphp
                    <td style="font-size:11px;vertical-align:top;padding:4px;">
                        @foreach($citasSlot as $c)
                        <div style="background:{{ match($c->estado) { 'confirmada' => '#2ecc7130', 'en_curso' => '#3498db30', 'completada' => '#95a5a630', 'cancelada' => '#e74c3c20', default => '#f39c1230' } }};padding:3px 6px;border-radius:4px;margin-bottom:2px;border-left:3px solid {{ match($c->estado) { 'confirmada' => '#2ecc71', 'en_curso' => '#3498db', 'completada' => '#95a5a6', 'cancelada' => '#e74c3c', default => '#f39c12' } }};">
                            <strong>{{ $c->mecanico->nombre ?? '' }}</strong><br>{{ Str::limit($c->cliente_nombre, 15) }}
                        </div>
                        @endforeach
                    </td>
                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

{{-- Leyenda + filtros --}}
<div style="display:flex;gap:16px;margin-bottom:12px;font-size:11px;flex-wrap:wrap;">
    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#f39c12;margin-right:3px;"></span>Pendiente</span>
    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#2ecc71;margin-right:3px;"></span>Confirmada</span>
    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#3498db;margin-right:3px;"></span>En curso</span>
    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#95a5a6;margin-right:3px;"></span>Completada</span>
    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#e74c3c;margin-right:3px;"></span>Cancelada</span>
</div>

<form action="{{ route('citas.index') }}" method="GET" class="vx-search-box">
    <input type="text" name="search" class="vx-input" placeholder="Buscar cliente o vehículo..." value="{{ request('search') }}" style="flex:1;">
    <select name="taller_id" class="vx-select" style="width:auto;"><option value="">Todos los talleres</option>@foreach($talleres as $t)<option value="{{ $t->id }}" {{ request('taller_id') == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>@endforeach</select>
    <select name="estado" class="vx-select" style="width:auto;"><option value="">Todos</option>@foreach(\App\Models\CitaTaller::$estados as $k => $v)<option value="{{ $k }}" {{ request('estado') == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select>
    <button type="submit" class="vx-btn vx-btn-primary"><i class="bi bi-search"></i></button>
    @if(request()->anyFilled(['search','taller_id','estado']))<a href="{{ route('citas.index') }}" class="vx-btn vx-btn-secondary">Limpiar</a>@endif
</form>

{{-- Tabla --}}
<div class="vx-card"><div class="vx-card-body" style="padding:0;">
    @if($citas->count() > 0)
    <div class="vx-table-wrapper"><table class="vx-table">
        <thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Vehículo</th><th>Mecánico</th><th>Taller</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>@foreach($citas as $c)
        <tr>
            <td style="font-size:12px;">{{ $c->fecha->format('d/m/Y') }}</td>
            <td style="font-family:var(--vx-font-mono);font-size:12px;">{{ substr($c->hora_inicio, 0, 5) }}@if($c->hora_fin)–{{ substr($c->hora_fin, 0, 5) }}@endif</td>
            <td style="font-weight:600;">{{ Str::limit($c->cliente_nombre, 25) }}</td>
            <td style="font-size:12px;">{{ $c->vehiculo_info ?? '—' }}</td>
            <td style="font-size:12px;">{{ $c->mecanico->nombre_completo ?? '—' }}</td>
            <td style="font-size:12px;">{{ $c->taller->nombre ?? '—' }}</td>
            <td>@switch($c->estado) @case('pendiente')<span class="vx-badge vx-badge-warning">Pendiente</span>@break @case('confirmada')<span class="vx-badge vx-badge-success">Confirmada</span>@break @case('en_curso')<span class="vx-badge vx-badge-info">En Curso</span>@break @case('completada')<span class="vx-badge vx-badge-gray">Completada</span>@break @case('cancelada')<span class="vx-badge vx-badge-danger">Cancelada</span>@break @endswitch</td>
            <td><div class="vx-actions"><button class="vx-actions-toggle"><i class="bi bi-three-dots-vertical"></i></button><div class="vx-actions-menu">
                @can('editar citas')<a href="{{ route('citas.edit', $c) }}"><i class="bi bi-pencil" style="color:var(--vx-warning);"></i> Editar</a>@endcan
                @can('eliminar citas')<form action="{{ route('citas.destroy', $c) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">@csrf @method('DELETE')<button type="submit" class="act-danger"><i class="bi bi-trash"></i> Eliminar</button></form>@endcan
            </div></div></td>
        </tr>@endforeach</tbody>
    </table></div>
    <div style="padding:16px 20px;">{{ $citas->links('vendor.pagination.vexis') }}</div>
    @else<div class="vx-empty"><i class="bi bi-calendar-check"></i><p>No se encontraron citas.</p></div>@endif
</div></div>
@endsection
