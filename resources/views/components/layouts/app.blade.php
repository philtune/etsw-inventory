@props([
	'pageTitle' => Str::headline(request()->path())
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $pageTitle }} - Etsy App 25Q1</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
</head>
<body class="l_body">
<nav style="margin-bottom: 1.5rem;">
	<a href="{{ route('home') }}">Home</a> |
	<a href="{{ route('listings.index') }}">Listings</a> |
	<a href="#">Orders</a> |
	<a href="#">Transactions</a>
</nav>
<h1>{{ $pageTitle }}</h1>
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
