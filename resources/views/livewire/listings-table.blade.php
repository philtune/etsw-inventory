<div>
	{{ $listings->links('pagination') }}
	<table style="width:100%">
		<thead>
		<tr>
			<th>Edit</th>
			<th>Title</th>
			<th>Price</th>
			<th>State</th>
			<th>Views</th>
			<th>Favorited</th>
			<th>In Stock</th>
		</tr>
		</thead>
		<tbody>
		@foreach( $listings as $listing )
			<tr>
				<td>
					<form method="POST" action="{{ route('listings.update', $listing) }}">
						@csrf
						@method('PATCH')
						<select name="product_type_id">
							<option>-- Product Type --</option>
							@foreach( \App\Models\ProductType::get(['id', 'code', 'label']) as $productType )
								<option
									value="{{ $productType->id }}"
									@selected(old('product_type_id', $listing->product_type_id) === $productType->id)
								>[{{ $productType->code }}] {{ $productType->label }}</option>
							@endforeach
						</select>
						<button type="submit">Update</button>
					</form>
				</td>
				<td title="{{ $listing->title }}">{{ Str::limit($listing->title, 48) }}</td>
				<td>{{ $listing->price_formatted }}</td>
				<td>{{ $listing->state }}</td>
				<td>{{ $listing->views }}</td>
				<td>{{ $listing->num_favorers }}</td>
				<td>{{ $listing->quantity }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{{ $listings->links('pagination') }}
</div>
