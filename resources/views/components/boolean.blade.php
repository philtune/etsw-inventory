@props([
	/** @var boolean */
	'default'
])
@if( $default )
	<span data-tooltip="Yes">@svg('icon-check', 'text-success')</span>
@else
	<span data-tooltip="No">@svg('icon-xmark', 'text-danger')</span>
@endif
