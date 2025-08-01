@props([
    'title' => null,
	'trigger',
    'uid' => 'id_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'submit' => null
])
<x-modal-post
	:title="$title ?: 'Edit ' . $modelName"
	:$uid
	:$pushTo
	:submit="$submit ?: 'Update ' . $modelName"
	:$action
	method="PATCH"
>
	<x-slot:trigger
		class="--bare"
	>@svg('icon-pencil') Edit {{ $modelName }}</x-slot:trigger>
	{{ $slot }}
</x-modal-post>
