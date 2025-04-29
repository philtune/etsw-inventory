<x-layouts.app page-title="Home">
	@if( $access_token )
		<table class="m_table">
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
					<a href="{{ route('etsy-api.api-refresh-token') }}">Refresh Token</a>
				</td>
			</tr>
		</table>
		<p><a href="{{ route('etsy-api.import-all') }}">Import Everything</a></p>
		<table class="m_table">
			<tr>
				<th>Listings (<a href="{{ route('etsy-api.import-listings') }}">Import</a>)</th>
				<td><strong>{{ number_format($listing_count) }}</strong> <small>(as of {{ $listing_latest->diffForHumans(short: true) }}, {{ $listing_latest->format('Y-m-d g:i a') }})</small></td>
			</tr>
			<tr>
				<th>Receipts (Orders) (<a href="{{ route('etsy-api.import-receipts') }}">Import</a>)</th>
				<td><strong>{{ number_format($receipt_count) }}</strong> <small>(as of {{ $receipt_latest->diffForHumans(short: true) }}, {{ $receipt_latest->format('Y-m-d g:i a') }})</small></td>
			</tr>
			<tr>
				<th>Transactions (<a href="{{ route('etsy-api.import-transactions') }}">Import</a>)</th>
				<td><strong>{{ number_format($transaction_count) }}</strong> <small>(as of {{ $transaction_latest->diffForHumans(short: true) }}, {{ $transaction_latest->format('Y-m-d g:i a') }})</small></td>
			</tr>
			<tr>
				<th>Revenue to Date</th>
				<td><strong>${{ number_format($revenue_to_date, 2) }}</strong></td>
			</tr>
		</table>
	@else
		<a href="{!! etsy_oauth_connect_url() !!}">Connect to Etsy account</a>
	@endif
</x-layouts.app>
