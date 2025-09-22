<div>
	<div class="l_cols --split">
		<div>{{ $products->links('pagination') }}</div>
		<div class="l_cols --md">
			<button
				type="button"
				wire:click="updateAggregates"
				class="u_btn --warning --sm">
				@svg('icon-rotate') Update Aggregates
			</button>
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
		</div>
	</div>
	<div class="m_table__container">
		<table class="m_table --sheet">
			<thead>
			<tr>
				<th>Etsy Listings</th>
				<th class="w-1px">Type</th>
				<th class="w-1px">Scent</th>
				<x-th.sortable label="Product Label" column="products.label"/>
				<x-th.sortable label="Notes" column="products.notes"/>
				<th class="w-1px">Stock</th>
				<th><small>Etsy<br/>Stock</small></th>
				<x-th.sortable column="product_aggregates.etsy_transactions_qty" desc-first>
					<x-slot:label><small>Sold:<br/>Etsy</small></x-slot:label>
				</x-th.sortable>
				<x-th.sortable column="product_aggregates.etsy_revenue" desc-first>
					<x-slot:label><small>Revenue:<br/>Etsy</small></x-slot:label>
				</x-th.sortable>
				<x-th.sortable column="product_aggregates.wholesale_order_products_qty" desc-first>
					<x-slot:label><small>Sold:<br/>Wholesale</small></x-slot:label>
				</x-th.sortable>
				<x-th.sortable column="product_aggregates.wholesale_revenue" desc-first>
					<x-slot:label><small>Revenue:<br/>Wholesale</small></x-slot:label>
				</x-th.sortable>
				<th><small class="l_cols --inline">Sold:<br/>TOTAL</small></th>
				<x-th.sortable column="product_aggregates.total_revenue" desc-first>
					<x-slot:label><small>Revenue:<br/>TOTAL</small></x-slot:label>
				</x-th.sortable>
			</tr>
			</thead>
			<tbody>
			@foreach( $products as $product )
				@php($firstListing = $product->etsyListings->first())
				<tr wire:key="product_{{ $product->id }}">
					<td data-tooltip="Etsy Listings" class="text-center">
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
						<td data-tooltip="Is Bundle?" colspan="2">BUNDLE</td>
					@else
						<td data-tooltip="Type" class="text-center">{{ $product->productType?->code ?: '??' }}</td>
						<td data-tooltip="Scent" class="text-center">{{ $product->scent?->code ?: '??' }}</td>
					@endif
					<td data-tooltip="Label">
						<span data-tooltip="{{ $product->label }}">{{ Str::limit($product->label, 48) }}</span>
					</td>
					<td data-tooltip="Notes">
						<span data-tooltip="{{ $product->notes }}">{{ Str::limit($product->notes, 24) }}</span>
					</td>
					<td data-tooltip="Stock" class="text-right">
						@include('products.partials.stock')
					</td>
					<td data-tooltip="Etsy Stock" class="text-right">
						@include('products.partials.etsy-stock')
					</td>
					<td data-tooltip="Sold: Etsy" class="text-center">{{ $product->productAggregate?->etsy_transactions_qty ?: 0 }}</td>
					<td data-tooltip="Revenue: Etsy" class="text-right">${{ number_format($product->productAggregate?->etsy_revenue, 2) }}</td>
					<td data-tooltip="Sold: Wholesale" class="text-center">{{ $product->productAggregate?->wholesale_order_products_qty ?: 0 }}</td>
					<td data-tooltip="Revenue: Wholesale" class="text-right">${{ number_format($product->productAggregate?->wholesale_revenue ?: 0, 2) }}</td>
					<td data-tooltip="Sold: TOTAL" class="text-center">
						<strong>{{ ($product->productAggregate?->etsy_transactions_qty ?: 0) + ($product->productAggregate?->wholesale_order_products_qty ?: 0) }}</strong>
					</td>
					<td data-tooltip="Revenue: TOTAL" class="text-right"><strong>${{ number_format($product->productAggregate?->total_revenue, 2) }}</strong></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $products->links('pagination') }}
</div>
