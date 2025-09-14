<x-layouts.index-table :$stack_id :$collection>
	<x-slot:before>
		<x-modal.wire.create
			:push-to="$stack_id"
			model-name="Product Type"
			uid="create_product_type"
		>
			@include('product-types.form-inputs')
		</x-modal.wire.create>
	</x-slot:before>
	<x-slot:headers>
		<x-th.sortable label="Code" column="code"/>
		<x-th.sortable label="Label" column="label"/>
		<th>Variants</th>
		<x-th.sortable label="Products" column="products_count" desc-first/>
		<x-th.sortable label="Etsy" column="etsy_listings_count" desc-first/>
		<x-th.sortable label="Revenue" column="total_revenue" desc-first/>
	</x-slot:headers>
	@foreach( $collection as $productType )
		<x-index-table-row
			:model="$productType"
			model-name="Product Type"
			:$stack_id
			:$loop
		>
			<x-slot:editWireModal>
				@include('product-types.form-inputs')
			</x-slot:editWireModal>
			<td>{{ $productType->label }}</td>
			<td>{{ $productType->code }}</td>
			<td>
				@if( $productType->variant_label || $productType->variants->isNotEmpty() )
					<div>{{ $productType->variant_label ?: '[Unlabelled]' }}:</div>
					@foreach( $productType->variants as $productTypeVariant )
						@if( $productTypeVariant->is_archived )
							<div class="--tt-left" data-tooltip="archived"><span class="opacity-50">{{ $productTypeVariant->label }}</span></div>
						@else
							<div>
								@if( $productTypeVariant->id === $productType->defaultVariant->id )
									@svg('icon-check')
								@endif
								{{ $productTypeVariant->label }}
							</div>
						@endif
					@endforeach
				@endif
			</td>
			<td>
				{{ $productType->products_count }}
			</td>
			<td onclick="event.stopPropagation()" style="cursor:initial">
				<a
					class="u_btn --sm"
					href="{{ route('etsy.listings.index', ['product_type_id' => $productType->id]) }}"
				><img src="{{ asset('img/etsy-favicon.ico') }}" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> {{ $productType->etsy_listings_count }} listing(s)</a>
			</td>
			<td class="text-right">${{ number_format($productType['total_revenue'], 2) }}</td>
		</x-index-table-row>
	@endforeach
</x-layouts.index-table>
