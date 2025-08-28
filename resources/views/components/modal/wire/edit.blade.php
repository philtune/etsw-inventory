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
		wire:submit.prevent="update('{{ $id }}', wire_form_data($event.target))"
		class="l_rows"
		id="{{ $uid }}_form"
	>
		{{ $slot }}
	</form>
	<x-slot:submitBtn form="{{ $uid }}_form">@svg('icon-check') {{ $submit ?: 'Update ' . $modelName }}</x-slot:submitBtn>
</x-modal>
@pushonce('below-body')
	<script>
		const wire_form_data = form => {
			const formObject = {}
			for ( let [key, value] of ( new FormData(form) ).entries() ) {
				if ( key.endsWith('[]') ) {
					key = key.slice(0, -2)
					if ( !formObject.hasOwnProperty(key) ) {
						formObject[key] = []
					}
					formObject[key].push(value)
				} else {
					// If the key doesn't exist, simply assign the value
					formObject[key] = value
				}
			}
			return formObject
		}
	</script>
@endpushonce
