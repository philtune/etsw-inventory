@props([
	'last' => false,
])
<td
	{{ $attributes->class(['text-right']) }}
	onclick="event.stopPropagation()"
	style="cursor:initial"
>
	<x-dropdown
		:class="$last ? '--left-bottom' : '--left'"
		:trigger="e(svg('icon-ellipsis-vertical'))"
	>
		{!! $slot !!}
	</x-dropdown>
</td>
