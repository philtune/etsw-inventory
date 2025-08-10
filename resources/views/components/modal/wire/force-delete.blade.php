@props([
    'uid' => 'force_delete_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
])
<x-modal
	title="Delete {{ $modelName }}"
	:$uid
	:$pushTo
>
	<x-slot:trigger
		class="--danger --bare"
	>@svg('icon-trash-xmark') PermaDelete {{ $modelName }}</x-slot:trigger>
	<p>
		You are about to permanently delete this {{ $modelName }}. Are you sure?
	</p>
	<x-slot:submitBtn
		{{ $attributes->only('wire:click') }}
		class="--danger"
	>@svg('icon-trash-xmark') Yes, Permanently Delete {{ $modelName }}</x-slot:submitBtn>
</x-modal>
