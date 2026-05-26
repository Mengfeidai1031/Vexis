@props(['campo', 'label'])

@php
    $currentSort = request('sort_by');
    $currentDir = request('sort_dir', 'asc');
    $isActive = $currentSort === $campo;
    // Cycle: none → asc → desc → none
    if ($isActive && $currentDir === 'asc') {
        $nextDir = 'desc';
        $nextSort = $campo;
    } elseif ($isActive && $currentDir === 'desc') {
        $nextDir = null;
        $nextSort = null;
    } else {
        $nextDir = 'asc';
        $nextSort = $campo;
    }
    $params = request()->except(['sort_by', 'sort_dir', 'page']);
    if ($nextSort) {
        $params['sort_by'] = $nextSort;
        $params['sort_dir'] = $nextDir;
    }
@endphp

<th>
    <a href="{{ request()->url() . '?' . http_build_query($params) }}" class="vx-sort-link {{ $isActive ? 'vx-sort-active' : '' }}" style="text-decoration:none;color:inherit;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;">
        {{ $label }}
        @if($isActive && $currentDir === 'asc')
            <i class="bi bi-sort-up" style="font-size:12px;color:var(--vx-primary);"></i>
        @elseif($isActive && $currentDir === 'desc')
            <i class="bi bi-sort-down" style="font-size:12px;color:var(--vx-primary);"></i>
        @else
            <i class="bi bi-arrow-down-up" style="font-size:10px;color:var(--vx-text-muted);opacity:0.4;"></i>
        @endif
    </a>
</th>

@once
@push('styles')
<style>
.vx-sort-link:hover { color: var(--vx-primary) !important; }
.vx-sort-link:hover .bi-arrow-down-up { opacity: 1 !important; color: var(--vx-primary) !important; }
</style>
@endpush
@endonce
