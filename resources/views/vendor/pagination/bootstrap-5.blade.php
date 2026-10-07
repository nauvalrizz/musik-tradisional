@if ($paginator->hasPages())
    <nav class="custom-pagination-wrap">

        {{-- INFO --}}
        <div class="pagination-info">
            <span>
                {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }}
                dari {{ $paginator->total() }}
            </span>
        </div>

        {{-- PAGINATION --}}
        <ul class="pagination custom-pagination">

            {{-- PREV --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹</a>
                </li>
            @endif

            {{-- NUMBERS --}}
            @foreach ($elements as $element)
                {{-- DOTS --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- PAGES --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- NEXT --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">›</span>
                </li>
            @endif

        </ul>
    </nav>
@endif
