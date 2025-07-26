@props([
	'name' => null,
	'options' => [],
	'default' => null,
	'initial' => '-- SELECT --',
	'routeName',
	'all' => null
])
<select
	name="{{ $name }}"
	{{ $attributes->class('select') }}
	onchange="window.location=event.target.value+location.hash"
	data-tomselect
>
	<option value="{{ $all }}">{{ $initial }}</option>
	@foreach( $options as $model_id => $label )
		<option
			value="{{ route($routeName, $model_id) }}"
			@selected($default === $model_id)
		>{{ $label }}</option>
	@endforeach
</select>
@pushonce('below-body')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const init = () => {
				document.querySelectorAll('[data-tomselect]').forEach(_me => {
					if ( !_me.tomselect ) {
						new TomSelect(_me, {
							maxOptions: null,
							plugins: ['dropdown_input'],
							allowEmptyOption: true,
							refreshThrottle: 0
						})
					}
				})
			}
			if ( typeof Livewire !== 'undefined' ) {
				Livewire.hook('morphed', init)
			}
			init()
		})
	</script>
@endpushonce
