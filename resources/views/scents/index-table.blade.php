@props([
	'stack_id' => 'stack_' . uniqid()
])
<div class="l_rows">
	<div class="l_cols --split" style="z-index:2">
		{{ $collection->links('pagination') }}
		<div class="l_cols">
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
			<label>
				<input
					type="checkbox"
					wire:model.live="trashed"
				/> Trashed
			</label>
		</div>
	</div>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<x-th.sortable label="Code" column="code"/>
				<x-th.sortable label="Label" column="label"/>
				<x-th.sortable label="Products" column="products_count" desc-first/>
				<x-th.sortable label="Etsy" column="etsy_listings_count" desc-first/>
				<x-th.sortable label="Revenue" column="total_revenue" desc-first/>
				<x-th.actions/>
			</tr>
			</thead>
			<tbody>
			@foreach( $collection as $scent )
				@php($uid = 'edit_' . $scent->id)
				<tr onclick="openModal('{{ $uid }}')" style="cursor:pointer">
					<td>{{ $scent->code }}</td>
					<td>{{ $scent->label }}</td>
					<td>{{ $scent->products_count }}</td>
					<td onclick="event.stopPropagation()" style="cursor:initial">
						<a
							class="u_btn --sm"
							href="{{ route('etsy.listings.index', ['scent_id' => $scent->id]) }}"
						><img src="https://www.etsy.com/images/favicon.ico" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> {{ $scent->etsy_listings_count }} listing(s)</a>
					</td>
					<td class="text-right">${{ number_format($scent['total_revenue'], 2) }}</td>
					<x-td.actions :last="$loop->last">
						@if( $scent->deleted_at )
							<x-form.restore
								:action="route('scents.restore', $scent)"
								model-name="Scent"
							/>
							<x-modal-force-delete
								:action="route('scents.force-delete', $scent)"
								model-name="Scent"
								uid="force_delete_{{ $scent->id }}"
								:push-to="$stack_id"
							/>
						@else
							<x-modal-edit
								:action="route('scents.update', $scent)"
								model-name="Scent"
								:$uid
								:push-to="$stack_id"
							>
								@include('scents.form-inputs')
							</x-modal-edit>
							<x-modal-delete
								:action="route('scents.delete', $scent)"
								model-name="Scent"
								uid="delete_{{ $scent->id }}"
								can-restore
								:push-to="$stack_id"
							/>
						@endif
					</x-td.actions>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $collection->links('pagination') }}
	@stack($stack_id)
</div>
