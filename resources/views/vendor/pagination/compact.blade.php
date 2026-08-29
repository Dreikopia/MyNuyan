@if ($paginator->hasPages())

    <div class="flex items-center gap-1">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="btn btn-xs btn-disabled">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-xs">
                Previous
            </a>
        @endif


        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" --}}
            @if (is_string($element))
                <span class="px-2 text-base-content/60">
                    {{ $element }}
                </span>
            @endif


            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn btn-xs btn-primary">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="btn btn-xs">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach


        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-xs">
                Next
            </a>
        @else
            <span class="btn btn-xs btn-disabled">
                Next
            </span>
        @endif

    </div>

@endif
