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
				@php($firstListing = $product->etsyListings->sortByDesc('created_at')->first())
				<tr wire:key="product_{{ $product->id }}">
					<td class="text-center">
						@if( $firstListing )
							<a href="{{ route('etsy.listings.index', [
								'product_type_id' => $product->product_type_id,
								'scent_id' => $product->scent_id
							]) }}"><img src="{{ $firstListing->thumbnail }}" alt="Thumbnail"/></a>
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
							@if( !$product->is_bundle )
								@if( $product->productType?->variants->isNotEmpty() )
									@foreach( $product->productType->variants->where('is_archived', false) as $productTypeVariant )
										<tr wire:ignore>
											<th>{{ $productTypeVariant->label }}</th>
											<td>
												<input
													type="number"
													class="input"
													id="{{ uniqid() }}-stock"
													value="{{ $product->getVariantStock($productTypeVariant) }}"
													wire:change.live.debounce="updateVariantStock('{{ $product->id }}', '{{ $productTypeVariant->id }}', $event.target.value)"
													onclick="this.select()"
												/>
											</td>
										</tr>
									@endforeach
								@else
									<tr wire:ignore>
										<th></th>
										<td>
											<input
												type="number"
												class="input"
												id="{{ $product->id }}-default-stock"
												value="{{ $product->stock }}"
												wire:change.live.debounce="updateDefaultStock('{{ $product->id }}', $event.target.value)"
											/>
										</td>
									</tr>
								@endif
							@else
								<tr>
									<th>&nbsp;</th>
									<td>
										{!! $product->bundle_stock ?? '<em>N/A</em>' !!}
									</td>
								</tr>
							@endif
							</tbody>
						</table>
					</td>
					<td class="text-right">
						<table class="m_table --stock">
							<tbody>
							@if( !$product->is_bundle && $firstListing && $product->productType?->variants->isNotEmpty() )
								@foreach( $product->productType->variants->where('is_archived', false) as $productTypeVariant )
									@php($etsy_inventory = $firstListing->variant_in_stock($productTypeVariant->aliases) ?? 0)
									@php($product_stock = $product->getVariantStock($productTypeVariant))
									<tr>
										<td>
											<output
												@class([
													'output',
													'bg-success' => $etsy_inventory == $product_stock,
													'bg-warning' => $etsy_inventory < $product_stock,
													'bg-danger' => $etsy_inventory > $product_stock,
												])
											>{{ $etsy_inventory }}</output>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td>
										@if( is_null($etsy_inventory = $firstListing->variants_in_stock['default'] ?? null) )
											<em>?N/A</em>
										@else
											<output
												@class([
													'output',
													'bg-success' => $etsy_inventory == $product->stock,
													'bg-warning' => $etsy_inventory < $product->stock,
													'bg-danger' => $etsy_inventory > $product->stock,
												])
											>{!! $etsy_inventory !!}</output>
										@endif
									</td>
								</tr>
							@endif
							</tbody>
						</table>
					</td>
					<td class="text-center">{{ $product->productAggregate?->etsy_transactions_qty ?: 0 }}</td>
					<td class="text-right">${{ number_format($product->productAggregate?->etsy_revenue, 2) }}</td>
					<td class="text-center">{{ $product->productAggregate?->wholesale_order_products_qty ?: 0 }}</td>
					<td class="text-right">${{ number_format($product->productAggregate?->wholesale_revenue ?: 0, 2) }}</td>
					<td class="text-center"><strong>{{ ($product->productAggregate?->etsy_transactions_qty ?: 0) + ($product->productAggregate?->wholesale_order_products_qty ?: 0) }}</strong></td>
					<td class="text-right"><strong>${{ number_format($product->productAggregate?->total_revenue, 2) }}</strong></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $products->links('pagination') }}
</div>
