@extends('layouts.app')
@section('title', $incidencia->codigo_incidencia . ' - Incidencias - VEXIS')
@section('content')
<div class="vx-page-header">
    <h1 class="vx-page-title">{{ $incidencia->codigo_incidencia }}</h1>
    <div class="vx-page-actions">
        @can('editar incidencias')<a href="{{ route('incidencias.edit', $incidencia) }}" class="vx-btn vx-btn-warning"><i class="bi bi-pencil"></i> Editar</a>@endcan
        @can('eliminar incidencias')
        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta incidencia?')">@csrf @method('DELETE')
            <button type="submit" class="vx-btn vx-btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
        </form>
        @endcan
        <a href="{{ route('incidencias.index') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

{{-- Header info (full width) --}}
<div class="vx-card" style="margin-bottom:16px;">
    <div class="vx-card-header"><h4>{{ $incidencia->titulo }}</h4></div>
    <div class="vx-card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;">
            <div>
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Prioridad</p>
                <p style="margin:2px 0;">@switch($incidencia->prioridad)
                    @case('baja')<span class="vx-badge" style="background:#e8f5e9;color:#2e7d32;">Baja</span>@break
                    @case('media')<span class="vx-badge vx-badge-info">Media</span>@break
                    @case('alta')<span class="vx-badge vx-badge-warning">Alta</span>@break
                    @case('critica')<span class="vx-badge vx-badge-danger">Crítica</span>@break
                @endswitch</p>
            </div>
            <div>
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Estado</p>
                <p style="margin:2px 0;">@switch($incidencia->estado)
                    @case('abierta')<span class="vx-badge" style="background:#fff3e0;color:#e65100;">Abierta</span>@break
                    @case('en_progreso')<span class="vx-badge vx-badge-info">En Progreso</span>@break
                    @case('resuelta')<span class="vx-badge vx-badge-success">Resuelta</span>@break
                    @case('cerrada')<span class="vx-badge" style="background:#eee;color:#666;">Cerrada</span>@break
                @endswitch</p>
            </div>
            <div>
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha apertura</p>
                <p style="margin:2px 0;font-size:12px;">{{ $incidencia->fecha_apertura->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Fecha cierre</p>
                <p style="margin:2px 0;font-size:12px;">{{ $incidencia->fecha_cierre?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- 2 columnas: Usuario | Técnico --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
    {{-- COLUMNA IZQUIERDA — Usuario --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(51,170,221,0.06);"><h4><i class="bi bi-person" style="color:var(--vx-primary);"></i> Datos del emisor</h4></div>
            <div class="vx-card-body">
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Emisor</p>
                <p style="font-weight:600;margin:2px 0 0;">{{ $incidencia->usuario?->nombre_completo ?? '—' }}</p>
                @if($incidencia->usuario?->email)
                <p style="font-size:11px;color:var(--vx-text-muted);margin:2px 0 0;">{{ $incidencia->usuario->email }}</p>
                @endif
            </div>
        </div>

        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(51,170,221,0.06);"><h4><i class="bi bi-chat-left-text" style="color:var(--vx-primary);"></i> Descripción del usuario</h4></div>
            <div class="vx-card-body">
                <div style="white-space:pre-wrap;font-size:13px;line-height:1.7;">{{ $incidencia->descripcion }}</div>
            </div>
        </div>

        @php $archivosUsuario = $incidencia->archivos->where('tipo', 'usuario'); @endphp
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(51,170,221,0.06);"><h4><i class="bi bi-paperclip" style="color:var(--vx-primary);"></i> Archivos del usuario ({{ $archivosUsuario->count() }})</h4></div>
            <div class="vx-card-body">
                @if($archivosUsuario->count() > 0)
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @foreach($archivosUsuario as $archivo)
                    <div style="display:flex;align-items:center;gap:8px;background:var(--vx-bg);padding:8px 12px;border-radius:6px;">
                        @php $ext = pathinfo($archivo->nombre_original, PATHINFO_EXTENSION); @endphp
                        <i class="bi {{ in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'bi-image' : (in_array($ext, ['pdf']) ? 'bi-file-earmark-pdf' : 'bi-file-earmark') }}" style="font-size:16px;color:var(--vx-primary);"></i>
                        <div style="flex:1;min-width:0;">
                            <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" style="color:var(--vx-primary);font-size:12px;font-weight:600;">{{ $archivo->nombre_original }}</a>
                            <div style="font-size:10px;color:var(--vx-text-muted);">{{ $archivo->user?->nombre_completo ?? '—' }} — {{ $archivo->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @can('eliminar incidencias')
                        <form action="{{ route('incidencias.eliminarArchivo', $archivo) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?')">@csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--vx-danger);font-size:14px;"><i class="bi bi-x-circle"></i></button>
                        </form>
                        @endcan
                    </div>
                    @endforeach
                </div>
                @else
                <p style="font-size:13px;color:var(--vx-text-muted);margin:0;">Sin archivos del usuario.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA — Técnico --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(142,68,173,0.06);"><h4><i class="bi bi-person-gear" style="color:#8e44ad;"></i> Técnico asignado</h4></div>
            <div class="vx-card-body">
                <p style="font-size:11px;color:var(--vx-text-muted);margin:0;">Técnico</p>
                <p style="font-weight:600;margin:2px 0 0;color:{{ $incidencia->tecnico ? 'var(--vx-text)' : 'var(--vx-text-muted)' }};">{{ $incidencia->tecnico?->nombre_completo ?? 'Sin asignar' }}</p>
                @if($incidencia->tecnico?->email)
                <p style="font-size:11px;color:var(--vx-text-muted);margin:2px 0 0;">{{ $incidencia->tecnico->email }}</p>
                @endif
            </div>
        </div>

        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(142,68,173,0.06);"><h4><i class="bi bi-tools" style="color:#8e44ad;"></i> Comentario del técnico</h4></div>
            <div class="vx-card-body">
                @if($incidencia->comentario_tecnico)
                <div style="background:#f5f0ff;padding:12px 16px;border-left:3px solid #8e44ad;border-radius:0 6px 6px 0;">
                    <div style="white-space:pre-wrap;font-size:13px;">{{ $incidencia->comentario_tecnico }}</div>
                </div>
                @else
                <p style="font-size:13px;color:var(--vx-text-muted);margin:0;">Sin comentarios del técnico aún.</p>
                @endif
            </div>
        </div>

        @php $archivosTecnico = $incidencia->archivos->where('tipo', 'tecnico'); @endphp
        <div class="vx-card">
            <div class="vx-card-header" style="background:rgba(142,68,173,0.06);"><h4><i class="bi bi-paperclip" style="color:#8e44ad;"></i> Archivos del técnico ({{ $archivosTecnico->count() }})</h4></div>
            <div class="vx-card-body">
                @if($archivosTecnico->count() > 0)
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @foreach($archivosTecnico as $archivo)
                    <div style="display:flex;align-items:center;gap:8px;background:var(--vx-bg);padding:8px 12px;border-radius:6px;">
                        @php $ext = pathinfo($archivo->nombre_original, PATHINFO_EXTENSION); @endphp
                        <i class="bi {{ in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'bi-image' : (in_array($ext, ['pdf']) ? 'bi-file-earmark-pdf' : 'bi-file-earmark') }}" style="font-size:16px;color:#8e44ad;"></i>
                        <div style="flex:1;min-width:0;">
                            <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" style="color:#8e44ad;font-size:12px;font-weight:600;">{{ $archivo->nombre_original }}</a>
                            <div style="font-size:10px;color:var(--vx-text-muted);">{{ $archivo->user?->nombre_completo ?? '—' }} — {{ $archivo->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @can('eliminar incidencias')
                        <form action="{{ route('incidencias.eliminarArchivo', $archivo) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?')">@csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--vx-danger);font-size:14px;"><i class="bi bi-x-circle"></i></button>
                        </form>
                        @endcan
                    </div>
                    @endforeach
                </div>
                @else
                <p style="font-size:13px;color:var(--vx-text-muted);margin:0;">Sin archivos del técnico.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Timeline (full width) --}}
<div class="vx-card">
    <div class="vx-card-header"><h4><i class="bi bi-clock-history"></i> Timeline</h4></div>
    <div class="vx-card-body">
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:8px;height:8px;border-radius:50%;background:#e65100;margin-top:5px;flex-shrink:0;"></div>
                <div>
                    <div style="font-size:12px;font-weight:600;">Incidencia abierta</div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">{{ $incidencia->fecha_apertura->format('d/m/Y H:i') }} por {{ $incidencia->usuario?->nombre_completo ?? '—' }}</div>
                </div>
            </div>
            @if($incidencia->tecnico_id)
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:8px;height:8px;border-radius:50%;background:#8e44ad;margin-top:5px;flex-shrink:0;"></div>
                <div>
                    <div style="font-size:12px;font-weight:600;">Técnico asignado: {{ $incidencia->tecnico?->nombre_completo }}</div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">{{ $incidencia->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
            @if($incidencia->comentario_tecnico)
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:8px;height:8px;border-radius:50%;background:var(--vx-primary);margin-top:5px;flex-shrink:0;"></div>
                <div>
                    <div style="font-size:12px;font-weight:600;">Comentario técnico añadido</div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">{{ $incidencia->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
            @if($incidencia->fecha_cierre)
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:8px;height:8px;border-radius:50%;background:var(--vx-success, #4caf50);margin-top:5px;flex-shrink:0;"></div>
                <div>
                    <div style="font-size:12px;font-weight:600;">Incidencia {{ $incidencia->estado }}</div>
                    <div style="font-size:11px;color:var(--vx-text-muted);">{{ $incidencia->fecha_cierre->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
