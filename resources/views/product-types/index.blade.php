<x-layouts.app>
	<div style="display:flex; gap:1rem">
		<form method="POST" action="{{ route('product-types.store') }}">
			@csrf
			<div>Code</div>
			<input
				class="input"
				name="code"
				maxlength="16"
			/>
			<div>Label</div>
			<input
				class="input"
				name="label"
				maxlength="255"
			/>
			<button type="submit" class="u_btn">Add</button>
		</form>
		<table class="m_table">
			<thead>
			<tr>
				<th>Code</th>
				<th>Label</th>
				<th>Update</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $productTypes as $productType )
				<tr>
					<td>
						<form id="form_{{ $productType->id }}" method="POST" action="{{ route('product-types.update', $productType) }}">
							@csrf
							@method('PATCH')
							<input
								class="input"
								name="code"
								value="{{ $productType->code }}"
								maxlength="16"
							/>
						</form>
					</td>
					<td>
						<input
							class="input"
							form="form_{{ $productType->id }}"
							name="label"
							value="{{ $productType->label }}"
							maxlength="255"
						/>
					</td>
					<td>
						<button
							type="submit"
							form="form_{{ $productType->id }}"
							class="u_btn"
						>Update</button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
