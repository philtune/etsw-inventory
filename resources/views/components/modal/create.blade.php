@props([
    'title' => null,
	'trigger',
    'uid' => 'id_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'submit' => null
])
<x-modal.post
	:title="$title ?: 'Add ' . $modelName"
	:$uid
	:$pushTo
	:submit="$submit ?: 'Add ' . $modelName"
	:$action
>
	<x-slot:trigger>@svg('icon-plus') Add {{ $modelName }}</x-slot:trigger>
	{{ $slot }}
</x-modal.post>
