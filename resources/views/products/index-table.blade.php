<x-layouts.index-table :$stack_id :$collection>
	<x-slot:before>
		<x-modal.wire.create
			:push-to="$stack_id"
			model-name="Product"
			uid="create_product"
		>
			@include('products.form-inputs')
		</x-modal.wire.create>
	</x-slot:before>
	<x-slot:filters>
		<label>
			<input
				type="checkbox"
				wire:model.live="show_archived"
			/> Show Archived
		</label>
	</x-slot:filters>
	<x-slot:headers>
		<th>&nbsp;</th>
		<x-th.sortable label="Label" column="products.label"/>
		<x-th.sortable label="Bundle" column="products.is_bundle" desc-first/>
		<x-th.sortable label="Type" column="product_types.label"/>
		<x-th.sortable label="Scent" column="scents.label"/>
	</x-slot:headers>
	@foreach( $collection as $product )
		<x-index-table-row
			:model="$product"
			model-name="Product"
			:$stack_id
			:$loop
			@class(['bg_highlight' => $product->is_archived])
		>
			<td onclick="event.stopPropagation()" style="cursor:initial" class="text-center">
				@if( $firstListing = $product->etsyListings->first() )
					<a href="{{ route('etsy.listings.index', [
						'product_type_id' => $product->product_type_id,
						'scent_id' => $product->scent_id
					]) }}"><img src="{{ $firstListing->thumbnail }}" alt="Thumbnail"/></a>
				@else
					<em>N/A</em>
				@endif
			</td>
			<td>
				@if($product->is_archived)
					<span class="--tt-right" data-tooltip="Archived">@svg('icon-box-archive', 'text-warning')</span>
				@endif
				{{ $product->label }}
			</td>
			<x-td.boolean :default="$product->is_bundle" yes-only/>
			@if( $product->is_bundle )
				<td colspan="2">
					<strong>Bundle</strong><br/>
					{!! $product->bundleItems->implode(fn(\App\Models\ProductBundleItem $productBundleItem) => $productBundleItem->childProduct?->label . ' (' . ( $productBundleItem->productTypeVariant?->label ?: 'default' ) . ')', '<br/>') !!}
				</td>
			@else
				<td @class(['bg_highlight' => !$product->product_type_id])>
					{{ $product->productType?->label }}
				</td>
				<td @class(['bg_highlight' => !$product->scent_id])>
					{{ $product->scent?->label }}
				</td>
			@endif
			<x-slot:editWireModal>
				@include('products.form-inputs')
			</x-slot:editWireModal>
			<x-slot:secondaryActions>
				<a
					class="u_btn --warning --bare"
					href="javascript:navigator.clipboard.writeText('{{ $product->id }}')"
					onclick="toast('Copied!', '--success')"
				>@svg('icon-copy') Copy Product ID</a>
				@if( $product->is_archived )
					<button
						type="button"
						class="u_btn --bare --success"
						wire:click="unarchive('{{ $product->id }}')"
					>@svg('icon-rotate-left') Unarchive
					</button>
				@else
					<button
						type="button"
						class="u_btn --bare --warning"
						wire:click="archive('{{ $product->id }}')"
					>@svg('icon-box-archive') Archive
					</button>
				@endif
			</x-slot:secondaryActions>
		</x-index-table-row>
	@endforeach
</x-layouts.index-table>
