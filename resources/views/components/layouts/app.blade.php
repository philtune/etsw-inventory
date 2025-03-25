<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Etsy App 25Q1</title>
	<link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
</head>
<body class="l_body">
@if ( session('status') )
	<div style="border: 1px solid;border-radius: 4px;padding:6px;">
		{!! session('status') !!}
	</div>
@endif
@if ( $errors->any() )
	<ul style="border: 1px solid;border-radius: 4px;padding:6px;">
		@foreach ( $errors->all() as $error )
			<li>Error: {!! $error !!}</li>
		@endforeach
	</ul>
@endif
{{ $slot }}
</body>
</html>
