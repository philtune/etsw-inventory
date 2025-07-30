<div>
	{{ $etsyTransactions->links('pagination') }}
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Date</th>
				<th>Listing</th>
				<th>Subtotal</th>
				<th>Coupon</th>
				<th>Total</th>
				<th>Variations</th>
				<th>Data</th>
				<th>Variation</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $etsyTransactions as $etsyTransaction )
				<tr>
					<td>{{ $etsyTransaction->created_at->format('n/d/Y g:ia') }}</td>
					<td>{{ $etsyTransaction->etsyListing?->product?->title }}</td>
					<td class="text-right">${{ $etsyTransaction->subtotal }}</td>
					<td class="text-right">-${{ $etsyTransaction->adjustments }}</td>
					<td class="text-right">${{ $etsyTransaction->total }}</td>
					<td><code>{{ json_encode($etsyTransaction->variations, JSON_PRETTY_PRINT) }}</code></td>
					<td><code>{{ json_encode($etsyTransaction->product_data, JSON_PRETTY_PRINT) }}</code></td>
					<td>
						@foreach(($etsyTransaction->variation ?: []) as $key => $value)
							{{ $key }}: {{ $value }}
						@endforeach
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $etsyTransactions->links('pagination') }}
</div>
