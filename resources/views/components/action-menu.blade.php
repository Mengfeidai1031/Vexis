@props(['label' => null])

<div class="vx-actions">
    <button type="button" class="vx-actions-toggle" aria-label="Acciones" aria-haspopup="menu" aria-expanded="false">
        <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
    </button>
    <div class="vx-actions-menu" role="menu">
        {{ $slot }}
    </div>
</div>
