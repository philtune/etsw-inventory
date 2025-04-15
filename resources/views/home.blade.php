<x-layouts.app>
	@if( $access_token )
		<table>
			<tr>
				<th>access_token</th>
				<td>
					{{ $access_token }}
					<small><a href="javascript:navigator.clipboard.writeText('{{ $access_token }}')">Copy</a></small>
				</td>
			</tr>
			<tr>
				<th>expires_at</th>
				<td>{{ $expires_at->diffForHumans(short:true) }} <small>({{ $expires_at->toString() }})</small></td>
			</tr>
			<tr>
				<th>refresh_token</th>
				<td>
					{{ $refresh_token }}
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="{{ route('etsy-api.refresh-token') }}">Refresh Token</a>
				</td>
			</tr>
		</table>
		<ul>
			<li><a href="{{ route('listings.import') }}">Import Listings</a> - <strong>{{ number_format($listing_count) }}</strong> <small>(as of {{ $listing_latest->diffForHumans(short: true) }}, {{ $listing_latest->format('Y-m-d g:i a') }})</small></li>
			<li><a href="{{ route('receipts.import') }}">Import Receipts (Orders)</a> - <strong>{{ number_format($receipt_count) }}</strong> <small>(as of {{ $receipt_latest->diffForHumans(short: true) }}, {{ $receipt_latest->format('Y-m-d g:i a') }})</small></li>
			<li><a href="{{ route('transactions.import') }}">Import Transactions</a> - <strong>{{ number_format($transaction_count) }}</strong> <small>(as of {{ $transaction_latest->diffForHumans(short: true) }}, {{ $transaction_latest->format('Y-m-d g:i a') }})</small></li>
			<li>Revenue to Date: <strong>${{ number_format($revenue_to_date, 2) }}</strong></li>
		</ul>
	@else
		<a href="{!! etsy_oauth_connect_url() !!}">Connect to Etsy account</a>
	@endif
</x-layouts.app>
