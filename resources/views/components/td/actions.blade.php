@props([
	/** @var \LaravelIdea\BladeLoops\_BladeLoop */
	'loop'
])
<td
	{{ $attributes->class(['text-right']) }}
	onclick="event.stopPropagation()"
	style="cursor:initial"
>
	<x-dropdown
		:class="$loop->last ? '--left-bottom' : ( $loop->first ? '--left-top' : '--left' )"
		:trigger="e(svg('icon-ellipsis-vertical'))"
	>
		{!! $slot !!}
	</x-dropdown>
</td>
