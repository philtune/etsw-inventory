<table class="m_table --stock">
	<tbody>
	@if( $firstListing?->is_inactive )
		<tr>
			<td>
				<output
					@class([
						'output',
//						'bg-success' => $etsy_inventory == $product->bundle_min_stock,
//						'bg-warning' => $etsy_inventory < $product->bundle_min_stock,
						'bg-danger',
					])
				>Inactive</output>
			</td>
		</tr>
	@else
		@if( $product->is_bundle )
			<tr>
				<td>
					@if( is_null($etsy_inventory = $firstListing->variants_in_stock['default'] ?? null) )
						<em>?N/A</em>
					@else
						<output
							@class([
								'output',
								'bg-success' => $etsy_inventory == $product->bundle_min_stock,
								'bg-warning' => $etsy_inventory < $product->bundle_min_stock,
								'bg-danger' => $etsy_inventory > $product->bundle_min_stock,
							])
						>{!! $etsy_inventory !!}</output>
					@endif
				</td>
			</tr>
		@elseif( ($productTypeVariants = $product->productType->variants->where('is_archived', false))->isNotEmpty() )
			@foreach( $productTypeVariants as $productTypeVariant )
				@php($etsy_inventory = $firstListing?->variant_in_stock($productTypeVariant->aliases) ?: 0)
				@php($product_stock = $product->getVariantStockCount($productTypeVariant))
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
					@php($etsy_inventory = $firstListing->variant_in_stock())
					<output
						@class([
							'output',
							'bg-success' => $etsy_inventory == $product->stock,
							'bg-warning' => $etsy_inventory < $product->stock,
							'bg-danger' => $etsy_inventory > $product->stock,
						])
					>{{ $etsy_inventory }}</output>
				</td>
			</tr>
		@endif
	@endif
	</tbody>
</table>
