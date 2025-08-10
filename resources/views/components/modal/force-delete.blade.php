@props([
    'uid' => 'force_delete_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'method' => null,
])
<x-modal
	title="Delete {{ $modelName }}"
	:$uid
	:$pushTo
>
	<x-slot:trigger
		class="--danger --bare"
	>@svg('icon-trash-xmark') PermaDelete {{ $modelName }}</x-slot:trigger>
	<form
		method="POST"
		action="{{ $action }}"
		class="l_rows"
		id="{{ $uid }}_form"
	>
		@csrf
		@method('DELETE')
		<p>
			You are about to permanently delete this {{ $modelName }}. Are you sure?
		</p>
	</form>
	<x-slot:submitBtn
		form="{{ $uid }}_form"
		class="--danger"
	>@svg('icon-trash-xmark') Yes, Permanently Delete {{ $modelName }}</x-slot:submitBtn>
</x-modal>
