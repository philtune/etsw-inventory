@props([
	/** @var \Illuminate\Pagination\LengthAwarePaginator */
	'paginator'
])
<p>
	Showing
	@if ($paginator->firstItem())
		{{ $paginator->firstItem() }} to {{ $paginator->lastItem() }}
	@else
		{{ $paginator->count() }}
	@endif
	of {{ $paginator->total() }} results
</p>
@if ($paginator->hasPages())
	<nav>
		<p>
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
				<span class="disabled" aria-disabled="true"><span>&laquo; Previous</span></span>
			@else
				<button
					type="button"
					class="__btn"
					wire:click="previousPage('{{ $paginator->getPageName() }}')"
					wire:loading.attr="disabled"
				>&laquo; Previous
				</button>
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
							<button
								type="button"
								class="__btn"
								wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
							>{{ $page }}</button>
						@endif
					@endforeach
				@endif
			@endforeach

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
				<button
					type="button"
					class="__btn"
					wire:click="nextPage('{{ $paginator->getPageName() }}')"
					wire:loading.attr="disabled"
					aria-label="@lang('pagination.next')"
				>Next &raquo;</button>
			@else
				<span class="disabled" aria-disabled="true"><span>Next &raquo;</span></span>
			@endif
		</p>
	</nav>
@endif
