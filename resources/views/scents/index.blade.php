<x-layouts.app>
	<x-slot:toolbar>
		<form
			method="POST"
			action="{{ route('scents.store') }}"
			class="l_cols --md"
		>
			@csrf
			<div>Code</div>
			<input
				class="input --sm"
				name="code"
				maxlength="16"
			/>
			<div>Label</div>
			<input
				class="input"
				name="label"
				maxlength="255"
			/>
			<button
				type="submit"
				class="u_btn"
			>Add Scent
			</button>
		</form>
	</x-slot:toolbar>
	<table class="m_table w-auto">
		<thead>
		<tr>
			<th>Code</th>
			<th>Label</th>
			<th>Update</th>
			<th>Etsy Listings</th>
			<th>Etsy Revenue</th>
		</tr>
		</thead>
		<tbody>
		@foreach( $scents as $scent )
			<tr>
				<td>
					<form id="form_{{ $scent->id }}" method="POST" action="{{ route('scents.update', $scent) }}">
						@csrf
						@method('PATCH')
						<input
							class="input --sm"
							name="code"
							value="{{ $scent->code }}"
							maxlength="16"
						/>
					</form>
				</td>
				<td>
					<input
						class="input"
						form="form_{{ $scent->id }}"
						name="label"
						value="{{ $scent->label }}"
						maxlength="255"
					/>
				</td>
				<td>
					<button
						type="submit"
						form="form_{{ $scent->id }}"
						class="u_btn"
					>Update
					</button>
				</td>
				<td style="text-align: right;">
					<a href="{{ route('etsy.listings.index', ['scent_id' => $scent->id]) }}">{{ $scent->unarchived_listings_count }} <small>({{ $scent->archived_listings_count }} archived)</small></a>
				</td>
				<td style="text-align: right;">
					${{ number_format($scent->revenue, 2) }}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</x-layouts.app>
