@props(['label' => null])

<div class="vx-actions">
    <button type="button" class="vx-actions-toggle" aria-label="Acciones">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="vx-actions-menu" role="menu">
        {{ $slot }}
    </div>
</div>
