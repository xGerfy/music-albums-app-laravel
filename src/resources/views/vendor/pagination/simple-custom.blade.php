@if ($paginator->hasPages())
    <nav class="flex justify-center items-center gap-1" aria-label="Pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center text-gray-300">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-gray-900 rounded-lg transition-colors">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-10 h-10 flex items-center justify-center text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="w-10 h-10 flex items-center justify-center text-gray-900 font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-gray-900 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-gray-900 rounded-lg transition-colors">&raquo;</a>
        @else
            <span class="w-10 h-10 flex items-center justify-center text-gray-300">&raquo;</span>
        @endif
    </nav>
@endif
