<x-layouts.app>
	@if( $refresh_token )
		<table>
			<tr>
				<th>access_token</th>
				<td>{{ $access_token }}</td>
			</tr>
			<tr>
				<th>expires_at</th>
				<td>{{ $expires_at->diffForHumans(short:true) }} <small>({{ $expires_at->toString() }})</small></td>
			</tr>
			<tr>
				<th>refresh_token</th>
				<td>{{ $refresh_token }}</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="{{ route('etsy-api.refresh-token') }}">Refresh Token</a>
				</td>
			</tr>
		</table>
	@else
		<a style="text-decoration:none" href="{!! etsy_oauth_connect_url() !!}">{!! etsy_oauth_connect_url() !!}</a>
	@endif
</x-layouts.app>
