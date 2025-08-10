<nav class="c_nav l_rows">
	@foreach( $items as $item )
		@if( $item['href'] ?? false )
			<a
				href="{{ $item['href'] }}"
				@class([
					'nav_link',
					'--active' => $item['active'] ?? false,
				])
			>{!! $item['label'] !!}</a>
		@elseif( $item['items'] ?? false )
			<fieldset class="l_rows">
				<legend>{!! $item['label'] !!}</legend>
				@foreach( $item['items'] as $_item )
					@if( $_item['href'] ?? false )
						<a
							href="{{ $_item['href'] }}"
							@class([
								'nav_link',
								'--active' => $_item['active'] ?? false,
							])
						>{!! $_item['label'] !!}</a>
					@elseif( is_string($_item) )
						{!! $_item !!}
					@endif
				@endforeach
			</fieldset>
		@elseif( is_string($item) )
			{!! $item !!}
		@endif
	@endforeach
</nav>
