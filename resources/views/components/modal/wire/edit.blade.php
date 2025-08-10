@props([
	'id',
	'modelName',
    'title' => null,
    'uid' => 'id_' . uniqid(),
    'pushTo' => 'below-body',
    'submit' => null
])
<x-modal
	:title="$title ?: 'Edit ' . $modelName"
	:$uid
	:$pushTo
>
	<x-slot:trigger
		class="--bare"
	>@svg('icon-pencil') Edit {{ $modelName }}</x-slot:trigger>
	<form
		wire:submit.prevent="update('{{ $id }}', Object.fromEntries(new FormData($event.target)))"
		class="l_rows"
		id="{{ $uid }}_form"
	>
		{{ $slot }}
	</form>
	<x-slot:submitBtn form="{{ $uid }}_form">@svg('icon-check') {{ $submit ?: 'Update ' . $modelName }}</x-slot:submitBtn>
</x-modal>
