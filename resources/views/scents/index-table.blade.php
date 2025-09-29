<x-layouts.livewire-table :$stack_id :$collection>
	<x-slot:before>
		<x-modal.wire.create
			:push-to="$stack_id"
			model-name="Scent"
			uid="create_scent"
		>
			@include('scents.form-inputs')
		</x-modal.wire.create>
	</x-slot:before>
	<x-slot:headers>
		<x-th.sortable label='Code' column='code'/>
		<x-th.sortable label='Label' column='label'/>
		<x-th.sortable label='Products' column='products_count' desc-first/>
		<x-th.sortable label='Etsy' column='etsy_listings_count' desc-first/>
		<x-th.sortable label='Revenue' column='total_revenue' desc-first/>
	</x-slot:headers>
	@foreach( $collection as $scent )
		<x-index-table-row
			:model="$scent"
			model-name="Scent"
			:$stack_id
			:$loop
		>
			<x-slot:editWireModal>@include('scents.form-inputs')</x-slot:editWireModal>
			<td>{{ $scent->code }}</td>
			<td>{{ $scent->label }}</td>
			<td>{{ $scent->products_count }}</td>
			<td onclick='event.stopPropagation()' style='cursor:initial'>
				<a
					class='u_btn --sm'
					href="{{ route('etsy.listings.index', ['scent_id' => $scent->id]) }}"
				><img src="{{ asset('img/etsy-favicon.ico') }}" style='width:1rem;height:1rem;display:inline-block;vertical-align:middle' alt='Etsy'/> {{ $scent->etsy_listings_count }} listing(s)</a>
			</td>
			<td class='text-right'>${{ number_format($scent['total_revenue'], 2) }}</td>
		</x-index-table-row>
	@endforeach
</x-layouts.livewire-table>
