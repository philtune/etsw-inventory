@props([
    'title',
	'trigger',
    'uid' => 'id_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'method' => null,
    'submit' => 'Submit',
])
<x-modal
	:$title
	:$uid
	:$pushTo
	:$trigger
>
	<form
		method="POST"
		action="{{ $action }}"
		class="l_rows"
		id="{{ $uid }}_form"
	>
		@csrf
		@if( $method )
			@method($method)
		@endif
		{{ $slot }}
	</form>
	<x-slot:submitBtn form="{{ $uid }}_form">@svg('icon-check') {{ $submit }}</x-slot:submitBtn>
</x-modal>
