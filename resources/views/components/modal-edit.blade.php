@props([
    'title' => null,
	'trigger',
    'uid' => 'id_' . uniqid(),
	'modelName',
    'pushTo' => 'below-body',
    'action',
    'submit' => 'Update'
])
<x-modal-post
	:title="$title ?: 'Edit ' . $modelName"
	:$uid
	:$pushTo
	:$submit
	:$action
	method="PATCH"
>
	<x-slot:trigger
		class="--tt-right"
		data-tooltip="Edit {{ $modelName }}"
	>@svg('icon-pencil')</x-slot:trigger>
	{{ $slot }}
</x-modal-post>
