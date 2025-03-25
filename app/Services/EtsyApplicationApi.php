<?php

namespace App\Services;

use Closure;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Validator;

class EtsyApplicationApi
{

	const CALLS_REMAINING_THIS_SECOND = 'etsy_calls_remaining_this_second';
	const CALLS_REMAINING_TODAY = 'etsy_calls_remaining_today';

	/**
	 * @param Closure():Response $callback
	 */
	protected static function send(Closure $callback):array
	{
		if ( cache(self::CALLS_REMAINING_TODAY) == 0 ) {
			throw new TooManyRequestsHttpException(3600, 'Proxy tried too many times. Try again later.');
		}
		if ( cache(self::CALLS_REMAINING_THIS_SECOND) == 0 ) {
			sleep(1);
		}
		$pendingRequest = Http
			::baseUrl('https://openapi.etsy.com/v3/application')
			->withToken(EtsyAuthService::getAccessToken())
			->withHeaders([
				'x-api-key' => config('services.etsy.api.key'),
			]);
		$response = $callback($pendingRequest);
		cache(self::CALLS_REMAINING_THIS_SECOND, $response->headers->get('X-Remaining-This-Second'));
		cache(self::CALLS_REMAINING_TODAY, $response->headers->get('X-Remaining-Today'));
		return $response->json();
	}

	/**
	 * @throws ConnectionException
	 */
	public static function transactions(array $params = []):array
	{
		$params = Validator::make($params, [
			'limit'  => 'integer|min:1',
			'offset' => 'integer|min:0',
		])->validate();
		return self::send(fn(PendingRequest $request) => $request
			->withUrlParameters(['shop_id' => config('services.etsy.shop_id')])
			->get('/shops/{shop_id}/transactions', [
				'limit'  => $params['limit'] ?? 100,
				'offset' => $params['offset'] ?? 6200,
			]));
	}
}
