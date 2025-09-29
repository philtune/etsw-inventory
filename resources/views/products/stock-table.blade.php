<x-layouts.livewire-table
	:$stack_id
	:$collection
	table-class="--sheet"
>
	<x-slot:filters>
		<label>
			<input
				type="checkbox"
				wire:model.live="include_archived"
			/> Include Archived
		</label>
	</x-slot:filters>
	<x-slot:headers>
		<th>&nbsp;</th>
		<th><small>Etsy Listings</small></th>
		<x-th.sortable label="Type" column="product_types.code"/>
		<x-th.sortable label="Scent" column="scents.code"/>
		<x-th.sortable label="Label" column="products.label"/>
		<th>Notes</th>
		<th>In Stock</th>
		<th><small>Etsy<br/>Stock</small></th>
		<x-th.sortable
			label="<small>Etsy<br/>Revenue LTM</small>"
			column="etsy_revenue"
			desc-first
		/>
		<x-th.sortable
			label="<small>Wholesale<br/>Revenue LTM</small>"
			column="wholesale_revenue"
			desc-first
		/>
		<x-th.sortable
			label="<small>Total<br/>Revenue LTM</small>"
			column="total_revenue"
			desc-first
		/>
	</x-slot:headers>
	@foreach( $collection as $i => $product )
		@php($firstListing = $product->etsyListings->first())
		<x-index-table-row
			:model="$product"
			model-name="Product"
			:$stack_id
			:$loop
			without-delete
			@class(['bg-warning' => $product->is_archived])
		>
			@if( $firstListing )
				<x-slot:editWireModal>
					@include('products.stock-form-inputs')
				</x-slot:editWireModal>
			@endif
			<td>{{ ( ( $collection->currentPage() - 1 ) * $collection->perPage() ) + $i + 1 }}</td>
			<td>
				@if( $firstListing )
					<a tabindex="-1" href="{{ route('etsy.listings.index', [
						'product_type_id' => $product->product_type_id,
						'scent_id' => $product->scent_id
					]) }}"><img src="{{ $firstListing->thumbnail }}" alt="Thumbnail"/></a>
				@else
					<em>No Etsy<br/>Listing</em>
				@endif
			</td>
			@if( $product->is_bundle )
				<td colspan="2">[BUNDLE]</td>
			@else
				<td>{{ $product['product_type_code'] }}</td>
				<td>{{ $product['scent_code'] }}</td>
			@endif
			<td>
				<span
					@if( Str::length($product->label) >= 48 )
						data-tooltip="{{ $product->label }}"
					@endif
				>{{ Str::limit($product->label, 48) }}</span>
			</td>
			<td><span data-tooltip="{{ $product->notes }}">{{ Str::limit($product->notes, 24) }}</span></td>
			<td
				class="text-right"
				onclick="event.stopPropagation()"
				style="cursor:initial"
			>@include('products.partials.stock')</td>
			<td class="text-right">@include('products.partials.etsy-stock')</td>
			<td class="text-right">${{ number_format($product['etsy_revenue'], 2) }}</td>
			<td class="text-right">${{ number_format($product['wholesale_revenue'], 2) }}</td>
			<td class="text-right"><b>${{ number_format($product['total_revenue'], 2) }}</b></td>
		</x-index-table-row>
	@endforeach
</x-layouts.livewire-table>
