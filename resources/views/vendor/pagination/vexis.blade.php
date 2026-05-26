@if ($paginator->hasPages())
    <nav class="vx-pagination" role="navigation" aria-label="Paginación">
        <ul class="vx-pagination-list">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="vx-page-item disabled"><span class="vx-page-link" aria-hidden="true"><i class="bi bi-chevron-left" aria-hidden="true"></i></span></li>
            @else
                <li class="vx-page-item"><a class="vx-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"><i class="bi bi-chevron-left" aria-hidden="true"></i></a></li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="vx-page-item disabled"><span class="vx-page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="vx-page-item active"><span class="vx-page-link" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li class="vx-page-item"><a class="vx-page-link" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="vx-page-item"><a class="vx-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente"><i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
            @else
                <li class="vx-page-item disabled"><span class="vx-page-link" aria-hidden="true"><i class="bi bi-chevron-right" aria-hidden="true"></i></span></li>
            @endif
        </ul>
        <div class="vx-pagination-info">
            Mostrando {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} de {{ $paginator->total() }}
        </div>
    </nav>
@endif
