<div class="l_cols">
	@if( !$firstListing )
		<em>N/A</em>
	@elseif( !$firstListing->is_active )
		<output data-tooltip="Inactive" class="output bg-danger text-danger">@svg('icon-store-slash')</output>
	@elseif( $etsy_inventory = $firstListing->variants_in_stock['default'] ?? null )
		@php($product_stock = $product['bundle_stock'] ?? $product->getDefaultVariantStockCount())
		<output
			@class([
				'output',
				'bg-success' => $product->is_made_to_order || $etsy_inventory == $product_stock,
				'bg-warning' => !$product->is_made_to_order && $etsy_inventory < $product_stock,
				'bg-danger' => !$product->is_made_to_order && $etsy_inventory > $product_stock,
			])
		>{!! $etsy_inventory !!}</output>
	@elseif( $product->variants?->isNotEmpty() )
		<table class="m_table --stock">
			<tbody>
			@foreach( $product->variants as $productTypeVariant )
				@php($etsy_inventory = $firstListing->variant_in_stock($productTypeVariant->aliases))
				@php($product_stock = $product->getVariantStockCount($productTypeVariant))
				<tr>
					<td>
						<output
							@class([
								'output',
								'bg-success' => $etsy_inventory != 0 && ( $product->is_made_to_order || $etsy_inventory == $product_stock ),
								'bg-warning' => $etsy_inventory != 0 && !$product->is_made_to_order && $etsy_inventory < $product_stock,
								'bg-danger' => $etsy_inventory == 0 || ( !$product->is_made_to_order && $etsy_inventory > $product_stock ),
							])
						>{{ $etsy_inventory }}</output>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<output class="output bg-danger">[issue with data]</output>
	@endif
	@if( $firstListing?->last_imported_at?->addDay()->isFuture() )
		<span class="text-success --tt-xs" data-tooltip="Up to date">@svg('icon-check')</span>
	@else
		<span class="text-danger --tt-sm" data-tooltip="May be out of date">@svg('icon-snooze')</span>
	@endif
</div>
