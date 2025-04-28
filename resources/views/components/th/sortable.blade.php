@props([
	'label',
	'column' => null,
	'key' => null
])
<th
	wire:click="{{ $key ? "orderByKey('$key')" : "orderBy('$column')" }}"
	{{ $attributes->style('cursor:pointer') }}
>
	{{ $label }}
	@if( $this->order_column === $column )
		<small class="text-muted">
			@if( $this->order_desc )
				&triangledown;
			@else
				&triangle;
			@endif
		</small>
	@endif
</th>
