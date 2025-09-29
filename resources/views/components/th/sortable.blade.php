@props([
	'label' => null,
	'column' => null,
	'key' => null,
	'descFirst' => false,
])
<th
	wire:click="{{ $key ? "orderByKey('$key', " . boolval($descFirst) . ")" : "orderBy('$column', " . boolval($descFirst) . ")" }}"
	{{ $attributes->style('cursor:pointer') }}
>
	<span class="l_cols --inline">
	{!! $label ?: $slot !!}
		@if( $this->order_column === $column )
			<small class="text-muted">
			@if( $this->order_desc )
					@svg('icon-arrow-down-z-a')
				@else
					@svg('icon-arrow-up-a-z')
				@endif
		</small>
		@endif
	</span>
</th>
