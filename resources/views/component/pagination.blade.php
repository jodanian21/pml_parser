@if ($paginator->lastPage() > 1)
<div class="">
    <ol class="pagination">
        {{-- Previous page link --}}
        <li class="page-item">
            <a class="page-link" @if (!$paginator->onFirstPage()) href="{{ $paginator->previousPageUrl() }}" @endif>&lsaquo;</a>
        </li>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item"><a class="page-link">{{ $element }}</a></li>
            @endif

            {{-- Array of links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item">
                        @if ($page == $paginator->currentPage())
                            <a class="page-link" href="#">{{ $page }}</a>
                        @else
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Next page link --}}
        <li class="page-item">
            <a class="page-link" @if ($paginator->hasMorePages()) href="{{ $paginator->nextPageUrl() }}" @endif>&rsaquo;</a>
        </li>
    </ol>
</div>
@endif
