@if( $product->is_bundle )
	{{ $product['bundle_stock'] }}
@elseif( $product->variants?->isNotEmpty() )
	<table class="m_table --stock w-auto" style="margin-left: auto">
		@foreach( $product->variants as $productTypeVariant )
			@php($recently_updated = $product->getVariantStock($productTypeVariant)?->stock_updated_at?->addDays(5)->isFuture())
			<tr>
				<th>
					{{ $productTypeVariant->label }}
				</th>
				@if( !$product->is_made_to_order )
					<td>
						@if( $recently_updated )
							<button
								class="u_btn --sm --muted"
								tabindex="-1"
								data-tooltip="Undo mark stock updated"
								wire:click="undoMarkVariantStockUpdated('{{ $product->id }}', '{{ $productTypeVariant->id }}')"
							>@svg('icon-rotate-left')</button>
						@else
							<button
								class="u_btn --sm --warning"
								tabindex="-1"
								data-tooltip="Mark stock updated"
								wire:click="markVariantStockUpdated('{{ $product->id }}', '{{ $productTypeVariant->id }}')"
							>@svg('icon-check')</button>
						@endif
					</td>
					<td wire:ignore>
						<input
							type="number"
							onwheel="event.preventDefault()"
							@class(['input'])
							id="stock-{{ uniqid() }}"
							value="{{ $product->getVariantStockCount($productTypeVariant) }}"
							min="0"
							wire:change.live.debounce="updateVariantStock('{{ $product->id }}', '{{ $productTypeVariant->id }}', $event.target.value)"
						/>
					</td>
				@endif
			</tr>
		@endforeach
	</table>
@elseif( !$product->is_made_to_order )
	@php($recently_updated = $product->stock_updated_at?->addDays(5)->isFuture())
	<table class="m_table --stock w-auto" style="margin-left: auto">
		<tr>
			<td>
				@if( $recently_updated )
					<button
						class="u_btn --sm --muted"
						tabindex="-1"
						data-tooltip="Undo mark stock updated"
						wire:click="undoMarkDefaultStockUpdated('{{ $product->id }}')"
					>@svg('icon-check')</button>
				@else
					<button
						class="u_btn --sm --warning"
						tabindex="-1"
						data-tooltip="Mark stock updated"
						wire:click="markDefaultStockUpdated('{{ $product->id }}')"
					>@svg('icon-check')</button>
				@endif
			</td>
			<td wire:ignore>
				<input
					@class(['input'])
					id="{{ $product->id }}-default-stock"
					value="{{ $product->stock }}"
					min="0"
					wire:change.live.debounce="updateDefaultStock('{{ $product->id }}', $event.target.value)"
				/>
			</td>
		</tr>
	</table>
@endif
