@props([
	'scrollTo' => 'body',
	/** @var \Illuminate\Pagination\LengthAwarePaginator */
	'paginator'
])
<?php
$scrollIntoViewJsSnippet = ( $scrollTo !== false ) ? "document.querySelector('$scrollTo').scrollIntoView()" : '';
?>
<div class="l_rows">
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
		<nav class="l_cols --sm --wrap">
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
				<button
					type="button"
					class="u_btn --sm"
					disabled
					aria-disabled="true"
					title="Previous"
				>@svg('icon-chevron-left')</button>
			@else
				<button
					type="button"
					class="u_btn --sm"
					wire:click="previousPage('{{ $paginator->getPageName() }}')"
					x-on:click="{{ $scrollIntoViewJsSnippet }}"
					wire:loading.attr="disabled"
					title="Previous"
				>@svg('icon-chevron-left')</button>
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
							<button
								type="button"
								class="u_btn --sm"
								disabled
								aria-current="page"
							><span>{{ $page }}</span></button>
						@else
							<button
								type="button"
								class="u_btn --sm"
								wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
								x-on:click="{{ $scrollIntoViewJsSnippet }}"
							>{{ $page }}</button>
						@endif
					@endforeach
				@endif
			@endforeach

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
				<button
					type="button"
					class="u_btn --sm"
					wire:click="nextPage('{{ $paginator->getPageName() }}')"
					x-on:click="{{ $scrollIntoViewJsSnippet }}"
					wire:loading.attr="disabled"
					aria-label="@lang('pagination.next')"
					title="Next"
				>@svg('icon-chevron-right')</button>
			@else
				<button
					type="button"
					class="u_btn --sm"
					disabled
					aria-disabled="true"
					title="Next"
				>@svg('icon-chevron-right')</button>
			@endif
		</nav>
	@endif
</div>
