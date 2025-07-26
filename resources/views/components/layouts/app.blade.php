@props([
	'pageTitle' => Str::headline(request()->path()),
	'toolbar' => null,
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
	<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.default.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
	<script src="https://kit.fontawesome.com/9b9b99bb33.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
</head>
<body class="l_body">
@include('components.layouts.nav')
<div style="display:flex;gap:1rem;justify-content:space-between;align-items:center;">
	<h1>{{ $pageTitle }}</h1>
	<div class="l_cols --md">{{ $toolbar }}</div>
</div>
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
@if( session('toast') )
	@push('scripts')
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				toast('{!! __(session('toast')) !!}', '--success')
			})
		</script>
	@endpush
@endif
<div class="m_card">
	{{ $slot }}
</div>
<div class="m_toast__container"></div>
<script type="module" src="{{ asset('js/scripts.js') }}"></script>
@stack('below-body')
</body>
</html>
