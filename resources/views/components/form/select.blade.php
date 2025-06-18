@props([
	'name' => null,
	'options' => [],
	'default' => null,
	'initial' => '-- SELECT --',
])
<select
	name="{{ $name }}"
	{{ $attributes->class('select') }}
	data-tomselect
>
	<option value="">{{ $initial }}</option>
	@foreach( $options as $value => $label )
		<option
			value="{{ $value }}"
			@selected($default === $value)
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
