@if ($paginator->hasPages())
    <nav>
	    <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
		    {!! __('Showing') !!}
		    @if ($paginator->firstItem())
			    <span class="font-medium">{{ $paginator->firstItem() }}</span>
			    {!! __('to') !!}
			    <span class="font-medium">{{ $paginator->lastItem() }}</span>
		    @else
			    {{ $paginator->count() }}
		    @endif
		    {!! __('of') !!}
		    <span class="font-medium">{{ $paginator->total() }}</span>
		    {!! __('results') !!}
	    </p>
	    <p>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></span>
            @else
                <span><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></span>
            @endif

		    {{-- Pagination Elements --}}
		    @foreach ($elements as $element)
			    {{-- "Three Dots" Separator --}}
			    @if (is_string($element))
				    <span class="disabled" aria-disabled="true"><span>{{ $element }}</span></span>
			    @endif

			    {{-- Array Of Links --}}
			    @if (is_array($element))
				    @foreach ($element as $page => $url)
					    @if ($page == $paginator->currentPage())
						    <span class="active" aria-current="page"><span>{{ $page }}</span></span>
					    @else
						    <span><a href="{{ $url }}">{{ $page }}</a></span>
					    @endif
				    @endforeach
			    @endif
		    @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <span><a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></span>
            @else
                <span class="disabled" aria-disabled="true"><span>@lang('pagination.next')</span></span>
            @endif
	    </p>
    </nav>
@endif
