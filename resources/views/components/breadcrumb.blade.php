@props(['items' => []])

<nav class="vx-breadcrumb" aria-label="Breadcrumb">
    <ol>
        @foreach($items as $i => $item)
            @php $isLast = $i === count($items) - 1; @endphp
            <li @if($isLast) aria-current="page" @endif>
                @if(!$isLast && isset($item['href']))
                    <a href="{{ $item['href'] }}">
                        @if(isset($item['icon']))<i class="bi {{ $item['icon'] }}"></i>@endif
                        {{ $item['label'] }}
                    </a>
                @else
                    <span>
                        @if(isset($item['icon']))<i class="bi {{ $item['icon'] }}"></i>@endif
                        {{ $item['label'] }}
                    </span>
                @endif
                @if(!$isLast)<i class="bi bi-chevron-right vx-breadcrumb-sep"></i>@endif
            </li>
        @endforeach
    </ol>
</nav>

@once
@push('styles')
<style>
.vx-breadcrumb { font-size: var(--vx-text-sm); color: var(--vx-text-muted); margin-bottom: var(--vx-space-3); }
.vx-breadcrumb ol { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
.vx-breadcrumb li { display: inline-flex; align-items: center; gap: 6px; }
.vx-breadcrumb a { color: var(--vx-text-secondary); display: inline-flex; align-items: center; gap: 4px; }
.vx-breadcrumb a:hover { color: var(--vx-primary); }
.vx-breadcrumb li[aria-current="page"] > span { color: var(--vx-text); font-weight: var(--vx-weight-semibold); }
.vx-breadcrumb-sep { font-size: 10px; color: var(--vx-text-muted); }
</style>
@endpush
@endonce
