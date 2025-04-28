<x-layouts.app>
	<div style="display:flex; gap:1rem">
		<form method="POST" action="{{ route('scents.store') }}">
			@csrf
			<div>Code</div>
			<input
				name="code"
				maxlength="16"
			/>
			<div>Label</div>
			<input
				name="label"
				maxlength="255"
			/>
			<button type="submit">Add</button>
		</form>
		<table>
			<thead>
			<tr>
				<th>Code</th>
				<th>Label</th>
				<th>Update</th>
				<th>Listings</th>
				<th>Revenue</th>
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
								name="code"
								value="{{ $scent->code }}"
								maxlength="16"
							/>
						</form>
					</td>
					<td>
						<input
							form="form_{{ $scent->id }}"
							name="label"
							value="{{ $scent->label }}"
							maxlength="255"
						/>
					</td>
					<td>
						<button type="submit" form="form_{{ $scent->id }}">Update</button>
					</td>
					<td style="text-align: right;">
						<a href="{{ route('listings.index', ['scent_id' => $scent->id]) }}">{{ $scent->unarchived_listings_count }}  / {{ $scent->archived_listings_count }} arch</a>
					</td>
					<td style="text-align: right;">
						${{ number_format($scent->revenue, 2) }}
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
