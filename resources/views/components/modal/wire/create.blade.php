@props([
	'pushTo',
	'modelName',
	'uid' => 'create_' . uniqid(),
])
<x-modal
	title="Add {{ $modelName }}"
	:$uid
	:push-to="$pushTo"
>
	<form
		class="l_rows"
		id="{{ $uid }}_form"
		wire:submit.prevent="store(Object.fromEntries(new FormData($event.target)))"
	>
		{!! $slot !!}
	</form>
	<x-slot:submitBtn form="{{ $uid }}_form">@svg('icon-check') Add {{ $modelName }}</x-slot:submitBtn>
</x-modal>
