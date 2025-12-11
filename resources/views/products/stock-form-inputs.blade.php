<input type="hidden" name="etsy_listing_id" value="{{ $firstListing->id }}"/>
<table class="m_table">
	<tr>
		<th>Product ID</th>
		<td>{{ $product->id }}</td>
	</tr>
	<tr>
		<th>Etsy Listing ID</th>
		<td>
			{{ $firstListing->id }}
			<button
				class="u_btn"
				type="button"
				wire:click="import('{{ $firstListing->id }}')"
			>Reimport from Etsy
			</button>
		</td>
	</tr>
</table>
@if( $product->is_made_to_order )
	<div class="u_alert">@svg('icon-triangle-exclamation') This product is made to order.</div>
@endif
<div>
	<table class="m_table --stock w-auto">
		<thead>
		<tr>
			@if( !$product->is_made_to_order )
				<th>Stock</th>
			@endif
			<th>Etsy Stock</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			@if( !$product->is_made_to_order )
				<td>
					<table class="m_table --sheet w-auto" style="margin-left: auto">
						@if( $product->is_bundle )
							<tr>
								<th><em>Bundle</em></th>
								<td>{{ $product['bundle_stock'] }}</td>
							</tr>
						@elseif( $product->variants?->isNotEmpty() )
							@foreach( $product->variants as $productTypeVariant )
								<tr>
									<th>{{ $productTypeVariant->label }}</th>
									<td>{{ $product->getVariantStockCount($productTypeVariant) }}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<th><em>Default</em></th>
								<td>{{ $product->stock }}</td>
							</tr>
						@endif
					</table>
				</td>
			@endif
			<td>
				<table class="m_table --sheet w-auto" style="margin-left: auto">
					<tbody>
					@foreach( $firstListing->inventory['products'] as $inventory_product )
						@php($item_key = $inventory_product['property_values'][0]['values'][0] ?? null)
						<tr>
							<th>
								@if( $item_key )
									{{ $item_key }}
								@else
									<em>Default</em>
								@endif
							</th>
							<td>
								<input
									type="number"
									onwheel="event.preventDefault()"
									@class(['input'])
									name="variants_in_stock[{{ $item_key ?: 'default' }}]"
									value="{{ $inventory_product['offerings'][0]['quantity'] }}"
									min="0"
								/>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
</div>
