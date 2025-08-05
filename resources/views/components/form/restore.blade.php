@props([
	'action',
	'modelName'
])
<form action="{{ $action }}" method="POST" {{ $attributes }}>
	@csrf
	@method('PATCH')
	<button
		type="submit"
		class="u_btn --warning --bare"
	>@svg('icon-rotate-left') Restore {{ $modelName }}
	</button>
</form>
