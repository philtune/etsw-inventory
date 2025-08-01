@props([
	'name' => null,
	'options' => [],
	'default' => null,
	'initial' => '-- SELECT --',
])
@php($default = collect(Arr::wrap($default)))
<select
	name="{{ $name }}"
	{{ $attributes->class('select') }}
	data-tomselect
>
	@if ( $initial !== false )
		<option value="">{{ $initial }}</option>
	@endif
	@foreach( $options as $value => $label )
		<option
			value="{{ $value }}"
			@selected($default->contains($value))
		>{{ $label }}</option>
	@endforeach
</select>
@pushonce('below-body')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const init = () => {
				document.querySelectorAll('[data-tomselect]').forEach(_me => {
					if ( _me.tomselect ) {
						_me.tomselect.destroy()
					}
					new TomSelect(_me, {
						maxOptions: null,
						plugins: ['dropdown_input'],
						allowEmptyOption: true,
						refreshThrottle: 0
					})
				})
			}
			if ( typeof Livewire !== 'undefined' ) {
				Livewire.hook('morphed', init)
			}
			init()
		})
	</script>
@endpushonce
