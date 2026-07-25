@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="pg-btn" style="opacity:.4;cursor:default;">&larr;</span>
        @else
            <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&larr;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg-btn" style="opacity:.55;cursor:default;">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-btn on" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg-btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">&rarr;</a>
        @else
            <span class="pg-btn" style="opacity:.4;cursor:default;">&rarr;</span>
        @endif
    </nav>
@endif
