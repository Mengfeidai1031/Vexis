@props([
    'type' => 'view',
    'href' => null,
    'icon' => null,
    'label' => null,
    'as' => null,
    'confirm' => null,
])

@php
    $iconMap = [
        'view' => 'bi-eye',
        'edit' => 'bi-pencil',
        'delete' => 'bi-trash',
        'approve' => 'bi-check-circle',
        'reject' => 'bi-x-circle',
        'download' => 'bi-download',
        'upload' => 'bi-upload',
        'pdf' => 'bi-file-earmark-pdf',
    ];
    $labelMap = [
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'approve' => 'Aprobar',
        'reject' => 'Rechazar',
        'download' => 'Descargar',
        'upload' => 'Subir',
        'pdf' => 'PDF',
    ];
    $iconClass = $icon ?? ($iconMap[$type] ?? 'bi-dot');
    $labelText = $label ?? ($labelMap[$type] ?? ucfirst($type));
    $actClass = 'act-' . $type;
    $confirmMsg = $confirm ?? ($type === 'delete' ? '¿Eliminar este elemento?' : null);
@endphp

@if($as === 'form')
    <form method="POST" action="{{ $href }}" style="display:inline;" @if($confirmMsg) onsubmit="return confirm('{{ $confirmMsg }}');" @endif>
        @csrf
        @if($type === 'delete')@method('DELETE')@endif
        <button type="submit" class="{{ $actClass }}" {{ $attributes }}>
            <i class="bi {{ $iconClass }}"></i> {{ $labelText }}
        </button>
    </form>
@else
    <a href="{{ $href ?? '#' }}" class="{{ $actClass }}" {{ $attributes }}>
        <i class="bi {{ $iconClass }}"></i> {{ $labelText }}
    </a>
@endif
