<div>
	{{ $etsyTransactions->links('pagination') }}
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Listing</th>
				<th>Subtotal</th>
				<th>Coupon</th>
				<th>Total</th>
				<th>Variations</th>
				<th>Variation</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $etsyTransactions as $etsyTransaction )
				<tr>
					<td>{{ $etsyTransaction->etsyListing?->product?->title }}</td>
					<td class="text-right">${{ $etsyTransaction->subtotal }}</td>
					<td class="text-right">-${{ $etsyTransaction->adjustments }}</td>
					<td class="text-right">${{ $etsyTransaction->total }}</td>
					<td>@dump($etsyTransaction->variations[0] ?? null)</td>
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
