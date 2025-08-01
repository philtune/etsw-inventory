@props([
	'stack_id' => 'stack_' . uniqid()
])
<div>
	<div class="l_cols --split">
		<div>{{ $collection->links('pagination') }}</div>
		<div>
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
		</div>
	</div>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Label</th>
				<th>Code</th>
				<th>Variants</th>
				<th>Bundle</th>
				<th>Products</th>
				<th>Etsy</th>
				<x-th-actions/>
			</tr>
			</thead>
			<tbody>
			@foreach( $collection as $productType )
				@php($uid = 'edit_' . $productType->id)
				<tr onclick="openModal('{{ $uid }}')" style="cursor:pointer">
					<td>{{ $productType->label }}</td>
					<td>{{ $productType->code }}</td>
					<td>
						@if( $productType->variants )
							{{ $productType->variants['label'] ?? '[Unlabelled]' }}:<br/>
							{!! collect($productType->variants['options'] ?? [])->implode(fn($option, $f) => ( ($productType->variants['default'] ?? null) == $f ? e(svg('icon-check')) . ' ' : '' ) . $option, '<br/>') !!}
						@endif
					</td>
					<td>
						@if( $productType->is_bundle)
							{!! $productType->childProductTypes()->pluck('id')->implode(fn($id) => $child_product_type_options[$id], '<br/>') !!}
						@endif
					</td>
					<td>
						{{ $productType->products_count }}
					</td>
					<td onclick="event.stopPropagation()" style="cursor:initial">
						<a
							class="u_btn --sm"
							href="{{ route('etsy.listings.index', ['product_type_id' => $productType->id]) }}"
						><img src="https://www.etsy.com/images/favicon.ico" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> {{ $productType->etsy_listings_count }} listing(s)</a>
					</td>
					<td class="text-right" onclick="event.stopPropagation()" style="cursor:initial">
						<x-dropdown :class="$loop->last ? '--left-bottom' : '--left'">
							<x-slot:trigger>
								@svg('icon-ellipsis-vertical')
							</x-slot:trigger>

							<x-modal-edit
								model-name="Product Type"
								:action="route('product-types.update', $productType)"
								:$uid
								:push-to="$stack_id"
							>
								@include('product-types.form-inputs')
							</x-modal-edit>
							<x-modal-delete
								:action="route('product-types.delete', $productType)"
								model-name="Product Type"
								:can-restore="true"
								:push-to="$stack_id"
							/>
						</x-dropdown>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $collection->links('pagination') }}
	@stack($stack_id)
</div>
