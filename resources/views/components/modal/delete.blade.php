@props([
    'uid' => 'delete_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'method' => null,
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
	<form
		method="POST"
		action="{{ $action }}"
		class="l_rows"
		id="{{ $uid }}_form"
	>
		@csrf
		@method('DELETE')
		@if( is_null($canRestore) )
			{!! $text ?: $slot !!}
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
	</form>
	<x-slot:submitBtn
		form="{{ $uid }}_form"
		class="--danger"
	>@svg('icon-trash-xmark') Yes, Delete {{ $modelName }}</x-slot:submitBtn>
</x-modal>
