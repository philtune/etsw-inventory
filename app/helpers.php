<?php

if ( !function_exists( 'etsy_oauth_connect_url' ) ) {
	function etsy_oauth_connect_url():string
	{
		return \App\Services\EtsyAuthService::connectUrl();
	}
}
