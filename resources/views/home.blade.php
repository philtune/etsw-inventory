<x-layouts.app page-title="Home">
	<fieldset>
		<legend class="l_cols --sm"><img src="{{ asset('img/etsy-favicon.ico') }}" style="width:1rem;height:1rem;display:block" alt="Etsy"/> Etsy</legend>
		@if( $etsyOauthToken?->access_token )
{{--			<table class="m_table w-auto">--}}
{{--				<tr>--}}
{{--					<th>access_token</th>--}}
{{--					<td>--}}
{{--						{{ $etsyOauthToken->access_token }}--}}
{{--						<small><a href="javascript:navigator.clipboard.writeText('{{ $etsyOauthToken->access_token }}')">Copy</a></small>--}}
{{--					</td>--}}
{{--				</tr>--}}
{{--				<tr>--}}
{{--					<th>expires_at</th>--}}
{{--					<td>{{ $etsyOauthToken->expires_at->diffForHumans(short:true) }} <small>({{ $etsyOauthToken->expires_at->toString() }})</small></td>--}}
{{--				</tr>--}}
{{--				<tr>--}}
{{--					<th>refresh_token</th>--}}
{{--					<td>--}}
{{--						{{ $etsyOauthToken->refresh_token }}--}}
{{--					</td>--}}
{{--				</tr>--}}
{{--				<tr>--}}
{{--					<td colspan="2">--}}
{{--						<a class="u_btn --warning --sm" href="{{ route('etsy-api.api-refresh-token') }}">@svg('icon-rotate') Refresh Token</a>--}}
{{--					</td>--}}
{{--				</tr>--}}
{{--			</table>--}}
			<table class="m_table w-auto">
				<tr>
					<th>Listings</th>
					<td><strong>{{ number_format($listing_count) }}</strong> <small>(as of {{ $listing_latest->diffForHumans(short: true) }}, {{ $listing_latest->format('Y-m-d g:i a') }})</small></td>
					<td><a class="u_btn --accent --sm" href="{{ route('etsy-api.import-listings') }}">@svg('icon-file-import') Import</a></td>
				</tr>
				<tr>
					<th>Receipts (Orders)</th>
					<td><strong>{{ number_format($receipt_count) }}</strong> <small>(as of {{ $receipt_latest?->diffForHumans(short: true) }}, {{ $receipt_latest?->format('Y-m-d g:i a') }})</small></td>
					<td><a class="u_btn --accent --sm" href="{{ route('etsy-api.import-receipts') }}">@svg('icon-file-import') Import</a></td>
				</tr>
				<tr>
					<th>Transactions</th>
					<td><strong>{{ number_format($transaction_count) }}</strong> <small>(as of {{ $transaction_latest?->diffForHumans(short: true) }}, {{ $transaction_latest?->format('Y-m-d g:i a') }})</small></td>
					<td></td>
				</tr>
				<tr>
					<th>Revenue to Date</th>
					<td><strong>${{ number_format($revenue_to_date, 2) }}</strong></td>
					<td></td>
				</tr>
				<tr>
					<th>API calls remaining today</th>
					<td><strong>{{ number_format($etsyOauthToken->remaining_today) }}</strong></td>
					<td></td>
				</tr>
			</table>
		@else
			<a href="{!! etsy_oauth_connect_url() !!}">Connect to Etsy account</a>
		@endif
	</fieldset>
</x-layouts.app>
