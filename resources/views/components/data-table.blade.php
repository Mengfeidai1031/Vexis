@props([
    'empty' => 'No se encontraron resultados.',
    'emptyIcon' => 'bi-inbox',
    'emptyCta' => null,
    'emptyCtaLabel' => null,
    'count' => null,
])

@php
    $hasRows = is_null($count) ? true : ($count > 0);
@endphp

<div class="vx-card vx-card-table">
    <div class="vx-card-body" style="padding: 0;">
        @if($hasRows)
            <div class="vx-table-wrapper">
                <table class="vx-table">
                    @isset($head)
                        <thead>{{ $head }}</thead>
                    @endisset
                    <tbody>{{ $slot }}</tbody>
                </table>
            </div>
            @isset($pagination)
                <div class="vx-pagination-wrapper">{{ $pagination }}</div>
            @endisset
        @else
            <x-empty-state
                :icon="$emptyIcon"
                :message="$empty"
                :cta="$emptyCta"
                :ctaLabel="$emptyCtaLabel"
            />
        @endif
    </div>
</div>
