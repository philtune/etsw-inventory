@props([
	'stack_id' => 'stack_' . uniqid()
])
<div>
	<div class="l_cols --split">
		{{ $collection->links('pagination') }}
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
				<x-th.sortable label="Code" column="code"/>
				<x-th.sortable label="Label" column="label"/>
				<th>Variants</th>
				<th>Bundle</th>
				<x-th.sortable label="Products" column="products_count" desc-first/>
				<x-th.sortable label="Etsy" column="etsy_listings_count" desc-first/>
				<x-th.sortable label="Revenue" column="total_revenue" desc-first/>
				<x-th.actions/>
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
					<td class="text-right">${{ number_format($productType['total_revenue'], 2) }}</td>
					<x-td.actions :last="$loop->last">
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
					</x-td.actions>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $collection->links('pagination') }}
	@stack($stack_id)
</div>
