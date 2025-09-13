<div>
	{{ $etsyTransactions->links('pagination') }}
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Date</th>
				<th>Listing</th>
				{{--				<th>Variation</th>--}}
				<th>Subtotal</th>
				<th>Coupon</th>
				<th>Total</th>
				{{--				<th>Variations</th>--}}
				{{--				<th>Data</th>--}}
			</tr>
			</thead>
			<tbody>
			@foreach( $etsyTransactions as $etsyTransaction )
				<x-modal
					title="Transaction Details"
					uid="transaction-details-{{ $etsyTransaction->id }}"
					push-to="transactions-modals"
				>
					<code>{!! json_encode($etsyTransaction->meta, JSON_PRETTY_PRINT) !!}</code>
				</x-modal>
				<tr onclick="openModal('transaction-details-{{ $etsyTransaction->id }}')" style="cursor:pointer">
					<td>{{ $etsyTransaction->created_at->format('n/d/Y g:ia') }}</td>
					<td>
						<img src="{{ $etsyTransaction->etsyListing?->thumbnail }}" alt="Thumbnail" style="vertical-align: middle"/>
						&nbsp;
						{{ $etsyTransaction->etsyListing?->product?->title }}
						@if( $etsyTransaction->variation )
							[{{ $etsyTransaction->variation }}]
						@endif
					</td>
					{{--					<td>{{ $etsyTransaction->variation }}</td>--}}
					<td class="text-right">${{ $etsyTransaction->subtotal }}</td>
					<td class="text-right">-${{ $etsyTransaction->adjustments }}</td>
					<td class="text-right">${{ $etsyTransaction->total }}</td>
					{{--					<td><code>{{ json_encode($etsyTransaction->variations, JSON_PRETTY_PRINT) }}</code></td>--}}
					{{--					<td><code>{{ json_encode($etsyTransaction->product_data, JSON_PRETTY_PRINT) }}</code></td>--}}
				</tr>
			@endforeach
			</tbody>
		</table>
		@stack('transactions-modals')
	</div>
	{{ $etsyTransactions->links('pagination') }}
</div>
