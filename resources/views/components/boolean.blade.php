@props([
	/** @var boolean */
	'default',
	'yesOnly' => false,
])
@if( $default )
	<span data-tooltip="Yes">@svg('icon-check', 'text-success')</span>
@elseif( !$yesOnly )
	<span data-tooltip="No">@svg('icon-xmark', 'text-danger')</span>
@endif
