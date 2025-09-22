<table class="m_table --stock">
	<tbody>
	@if( $product->is_bundle )
		<tr>
			<th>&nbsp;</th>
			<td>
				{!! $product->bundle_stock ?? '<em>N/A</em>' !!}
			</td>
		</tr>
	@elseif( $product->productType?->variants->isNotEmpty() )
		@foreach( $product->productType->variants->where('is_archived', false) as $productTypeVariant )
			@php($recently_updated = $product->getVariantStock($productTypeVariant)?->stock_updated_at?->addDays(5)->isFuture())
			<tr>
				<th @class(['bg-warning' => !$recently_updated])>
					{{ $productTypeVariant->label }}
				</th>
				<td style="display:flex;align-items:center">
					@if( $recently_updated )
						<button
							class="u_btn --sm --muted"
							tabindex="-1"
							data-tooltip="Undo mark stock updated"
							wire:click="undoMarkVariantStockUpdated('{{ $product->id }}', '{{ $productTypeVariant->id }}')"
						>@svg('icon-rotate-left')</button>
					@else
						<button
							class="u_btn --sm --muted"
							tabindex="-1"
							data-tooltip="Mark stock updated"
							wire:click="markVariantStockUpdated('{{ $product->id }}', '{{ $productTypeVariant->id }}')"
						>@svg('icon-check')</button>
					@endif
					<div wire:ignore>
						<input
							@class(['input'])
							id="{{ uniqid() }}-stock"
							value="{{ $product->getVariantStockCount($productTypeVariant) }}"
							min="0"
							wire:change.live.debounce="updateVariantStock('{{ $product->id }}', '{{ $productTypeVariant->id }}', $event.target.value)"
						/>
					</div>
				</td>
			</tr>
		@endforeach
	@else
		@php($recently_updated = $product->stock_updated_at?->addDays(5)->isFuture())
		<tr>
			<th @class(['bg-warning' => !$recently_updated])></th>
			<td style="display:flex;align-items:center">
				@if( $recently_updated )
					<button
						class="u_btn --sm --muted"
						tabindex="-1"
						data-tooltip="Undo mark stock updated"
						wire:click="undoMarkDefaultStockUpdated('{{ $product->id }}')"
					>@svg('icon-check')</button>
				@else
					<button
						class="u_btn --sm --muted"
						tabindex="-1"
						data-tooltip="Mark stock updated"
						wire:click="markDefaultStockUpdated('{{ $product->id }}')"
					>@svg('icon-check')</button>
				@endif
				<div wire:ignore>
					<input
						@class(['input'])
						id="{{ $product->id }}-default-stock"
						value="{{ $product->stock }}"
						min="0"
						wire:change.live.debounce="updateDefaultStock('{{ $product->id }}', $event.target.value)"
					/>
				</div>
			</td>
		</tr>
	@endif
	</tbody>
</table>
