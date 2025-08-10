@props([
	'for',
	'modelName'
])
<button
	type="button"
	class="u_btn"
	onclick="openModal('{{ $for }}')"
>@svg('icon-plus') Add {{ $modelName }}</button>
