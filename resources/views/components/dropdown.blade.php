@props([
	'trigger',
	'tooltip' => null
])
<div {{ $attributes->class('c_dropdown') }}>
	<button
		type="button"
		class="__trigger"
	>{{ $trigger }}</button>
	<div class="c_dropdown-proxy_trigger" tabindex="0"></div>
	<div {{ $slot->attributes->class('c_dropdown-content') }} tabindex="0">
		{{ $slot }}
	</div>
</div>
