<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EtsyAuthService
{
	const ACCESS_TOKEN = 'etsy_access_token';
	const ACCESS_TOKEN_EXPIRES_AT = 'etsy_access_token_expires_at';
	const REFRESH_TOKEN = 'etsy_refresh_token';

	public static function connectUrl():string
	{
		return once(function ():string {
			session()->put('state', Str::random(40));
			return 'https://www.etsy.com/oauth/connect?' . http_build_query([
					'response_type'         => 'code',
					'client_id'             => config('services.etsy.api.key'),
					'redirect_uri'          => route('etsy-api.api-redirect-url'),
					'scope'                 => implode(' ', [
						'shops_r',
						'transactions_r',
						'listings_r',
						'email_r',
					]),
					'state'                 => session('state'),
					'code_challenge'        => config('services.etsy.api.code_challenge'),
					'code_challenge_method' => 'S256',
				], encoding_type: PHP_QUERY_RFC3986);
		});
	}

	public static function requestNewToken(array $params):bool
	{
		try {
			/**
			 * Access token typically expires in 3600 seconds (1 hour)
			 * @var array{
			 *      'access_token':string,
			 *      'token_type':string,
			 *      'expires_in':int,
			 *      'refresh_token':string,
			 *      'user_id':null,
			 *  } $response
			 */
			$response = Http
				::retry(2, 500)
				->post('https://api.etsy.com/v3/public/oauth/token', $params)
				->json();
			cache([self::ACCESS_TOKEN => $response['access_token']]);
			cache([self::ACCESS_TOKEN_EXPIRES_AT => now()->addSeconds($response['expires_in'])]);
			cache()->forever(self::REFRESH_TOKEN, $response['refresh_token']);
			return true;
		} catch ( ConnectionException ) {
			return false;
		}
	}

	public static function authCode(string $code):bool
	{
		return self::requestNewToken([
			'grant_type'    => 'authorization_code',
			'client_id'     => config('services.etsy.api.key'),
			'redirect_uri'  => route('etsy-api.api-redirect-url'),
			'code'          => $code,
			'code_verifier' => config('services.etsy.api.code_verifier'),
		]);
	}

	public static function refreshToken():bool
	{
		return self::requestNewToken([
			'grant_type'    => 'refresh_token',
			'client_id'     => config('services.etsy.api.key'),
			'refresh_token' => cache(self::REFRESH_TOKEN),
		]);
	}

	public static function getAccessToken():?string
	{
		if ( self::getCurrentTokenExpiresAt()?->isPast() ) {
			self::refreshToken();
		}
		return cache(self::ACCESS_TOKEN);
	}

	public static function getCurrentTokenExpiresAt():?Carbon
	{
		return cache(self::ACCESS_TOKEN_EXPIRES_AT);
	}

	public static function getRefreshToken():?string
	{
		return cache(self::REFRESH_TOKEN);
	}
}
