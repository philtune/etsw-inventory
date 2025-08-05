@props([
    'action',
	'modelName',
    'title' => null,
    'uid' => 'id_' . uniqid(),
    'pushTo' => 'below-body',
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
