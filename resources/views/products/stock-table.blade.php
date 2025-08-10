<div>
	<div class="l_cols --split">
		<div>{{ $products->links('pagination') }}</div>
		<div class="l_cols --md">
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
			<button
				type="button"
				wire:click="updateAggregates"
				class="u_btn --warning --sm">
				@svg('icon-rotate') Update Aggregates
			</button>
		</div>
	</div>
	<table class="m_table --sheet">
		<thead>
		<tr>
			<th>Etsy Listings</th>
			<th class="w-1px">Type</th>
			<th class="w-1px">Scent</th>
			<x-th.sortable label="Product Label" column="products.label"/>
			<th class="w-1px">Stock</th>
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
			<tr>
				<td class="text-center">
					@if( $firstListing = $product->etsyListings?->first() )
						<div data-tooltip="{{ $product->etsy_listings_count }} listings"><img src="{{ $firstListing->thumbnail }}" alt="Thumbnail"/></div>
					@else
						<em>N/A</em>
					@endif
				</td>
				<td class="text-center">{{ $product->productType?->code ?: '??' }}</td>
				<td class="text-center">{{ $product->scent?->code ?: '??' }}</td>
				<td>
					<span data-tooltip="{{ $product->label }}">{{ Str::limit($product->label, 48) }}</span>
				</td>
				<td class="text-right">
					<table class="m_table --stock">
						<tbody>
						@if( $product->can_stock )
							@if( $product->productType?->variants )
								@foreach( $product->productType->variants['options'] as $key => $label )
									<tr>
										<th>{{ $label }}</th>
										<td>
											<input
												type="number"
												class="input"
												id="{{ $product->id }}-{{ $key }}-stock"
												value="{{ $product->variants_in_stock[$key] ?? 0 }}"
												wire:change.blur="updateInStock('{{ $product->id }}', '{{ $key }}', $event.target.value)"
											/>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<th>&nbsp;</th>
									<td>
										<input
											type="number"
											class="input"
											id="{{ $product->id }}-{{ $key }}-stock"
											value="{{ $product->variants_in_stock['default'] ?? 0 }}"
											wire:change.blur="updateInStock('{{ $product->id }}', 'default', $event.target.value)"
										/>
									</td>
								</tr>
							@endif
						@else
							<tr>
								<th>&nbsp;</th>
								<td><em>N/A</em></td>
							</tr>
						@endif
						</tbody>
					</table>
				</td>
				<td class="text-center">{{ $product->productAggregate?->etsy_transactions_qty ?: 0 }}</td>
				<td class="text-right">${{ number_format($product->productAggregate?->etsy_revenue, 2) }}</td>
				<td class="text-center">{{ $product->productAggregate?->wholesale_order_products_qty ?: 0 }}</td>
				<td class="text-right">${{ number_format($product->productAggregate?->wholesale_revenue, 2) }}</td>
				<td class="text-center"><strong>{{ ($product->productAggregate?->etsy_transactions_qty ?: 0) + ($product->productAggregate?->wholesale_order_products_qty ?: 0) }}</strong></td>
				<td class="text-right"><strong>${{ number_format($product->productAggregate?->total_revenue, 2) }}</strong></td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{{ $products->links('pagination') }}
</div>
