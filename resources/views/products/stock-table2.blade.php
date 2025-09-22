<x-layouts.index-table :$stack_id :$collection>
	<x-slot:headers>
		<th>Etsy Listings</th>
		<x-th.sortable label="Type" column="product_types.code"/>
		<x-th.sortable label="Scent" column="scents.code"/>
		<x-th.sortable label="Label" column="products.label"/>
		<x-th.sortable label="Revenue" column="revenue"/>
		{{--		<th>Variants</th>--}}
		{{--		<x-th.sortable label="Products" column="products_count" desc-first/>--}}
		{{--		<x-th.sortable label="Etsy" column="etsy_listings_count" desc-first/>--}}
		{{--		<x-th.sortable label="Revenue" column="total_revenue" desc-first/>--}}
	</x-slot:headers>
	@foreach( $collection as $product )
		@php($firstListing = $product->etsyListings->first())
		<x-index-table-row
			:model="$product"
			model-name="Product"
			:$stack_id
			:$loop
			without-delete
		>
			<x-slot:editWireModal>
				hi
			</x-slot:editWireModal>
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
			<td>{{ $product->label }}</td>
			<td class="text-right">${{ number_format($product['revenue'], 2) }}</td>
		</x-index-table-row>
	@endforeach
</x-layouts.index-table>
