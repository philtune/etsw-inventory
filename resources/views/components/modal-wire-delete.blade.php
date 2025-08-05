@props([
    'uid' => 'id_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'text' => null,
    'canRestore' => null
])
<x-modal
	title="Delete {{ $modelName }}"
	:$uid
	:$pushTo
>
	<x-slot:trigger
		class="--danger --bare"
	>@svg('icon-trash-xmark') Delete {{ $modelName }}</x-slot:trigger>
	@if( is_null($canRestore) )
		{{ $slot }}
	@else
		<p>
			Are you sure you want to delete this {{ $modelName }}?
			@if( $canRestore)
				<span class="text-accent">(This can be undone.)</span>
			@else
				<span class="text-warning">(This is permanent and can not be undone.)</span>
			@endif
		</p>
	@endif
	<x-slot:submitBtn
		class="--danger"
		{{ $attributes->only('wire:click') }}
	>@svg('icon-trash-xmark') Yes, Delete {{ $modelName }}</x-slot:submitBtn>
</x-modal>
