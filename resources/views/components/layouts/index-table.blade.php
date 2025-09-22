@props([
	'stack_id',
	'collection',
	'headers',
	'before' => null,
	'after' => null,
	'withoutActions' => false,
	'filters' => null,
])
<div class="l_rows">
	{!! $before !!}
	<div class="l_cols --split" style="z-index:2">
		{{ $collection->links('pagination') }}
		<div class="l_cols">
			{!! $filters !!}
			<input
				type="search"
				wire:model.live="search"
				placeholder="Search"
				class="input"
			/>
			<label>
				<input
					type="checkbox"
					wire:model.live="trashed"
				/> Trashed
			</label>
		</div>
	</div>
	<div class="m_table__container">
		<table class='m_table'>
			<thead>
			<tr>
				{!! $headers !!}
				@if( !$withoutActions )
					<x-th.actions/>
				@endif
			</tr>
			</thead>
			<tbody>
			{!! $slot !!}
			</tbody>
		</table>
	</div>
	{{ $collection->links('pagination') }}
	@stack($stack_id)
	<input type="hidden" id="{{ $stack_id }}_errors" value="{{ json_encode($errors->all()) }}">
	@pushonce('below-body')
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				const _errors_input = document.getElementById('{{ $stack_id }}_errors')
				Livewire.hook('morphed', () => {
					JSON.parse(_errors_input.value).forEach(msg => toast(msg, '--danger'))
				})
			})
		</script>
	@endpushonce
	{!! $after !!}
</div>
