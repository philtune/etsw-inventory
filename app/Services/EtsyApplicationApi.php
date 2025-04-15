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
		if ( cache(self::CALLS_REMAINING_TODAY) === 0 ) {
			throw new TooManyRequestsHttpException(3600, 'Proxy tried too many times. Try again later.');
		}
		if ( cache(self::CALLS_REMAINING_THIS_SECOND) === 0 ) {
			sleep(1);
		}
		$pendingRequest = Http
			::baseUrl('https://openapi.etsy.com/v3/application')
			->withToken(EtsyAuthService::getAccessToken())
			->withHeaders([
				'x-api-key' => config('services.etsy.api.key'),
			]);
		$response = $callback($pendingRequest);
		cache([self::CALLS_REMAINING_THIS_SECOND => $response->getHeader('X-Remaining-This-Second')]);
		cache([self::CALLS_REMAINING_TODAY => $response->getHeader('X-Remaining-Today')]);
		return $response->json();
	}

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getListingsByShop
	 * @throws ConnectionException
	 */
	public static function listings(array $params = []):array
	{
		$params = Validator::make($params, [
			'state'      => 'string',
			'limit'      => 'integer|min:1',
			'offset'     => 'integer|min:0',
			'sort_on'    => 'string',
			'sort_order' => 'string',
			'includes'   => 'string',
		])->validate();
		return self::send(fn(PendingRequest $request) => $request
			->withUrlParameters(['shop_id' => config('services.etsy.shop_id')])
			->get('/shops/{shop_id}/listings', [
				'limit'  => $params['limit'] ?? 100,
				'offset' => $params['offset'] ?? 0,
				'state'  => $params['state'] ?? 'active',
			]));
	}

	/**
	 * @throws ConnectionException
	 */
	public static function receipts(array $params = []):array
	{
		$params = Validator::make($params, [
			'min_created'       => 'string',
			'max_created'       => 'string',
			'min_last_modified' => 'string',
			'max_last_modified' => 'string',
			'limit'             => 'integer|min:1',
			'offset'            => 'integer|min:0',
			'sort_on'           => 'string',
			'sort_order'        => 'string',
			'was_paid'          => 'string',
			'was_shipped'       => 'string',
			'was_delivered'     => 'string',
			'was_canceled'      => 'string',
		])->validate();
		return self::send(fn(PendingRequest $request) => $request
			->withUrlParameters(['shop_id' => config('services.etsy.shop_id')])
			->get('/shops/{shop_id}/receipts', [
				'limit'  => $params['limit'] ?? 100,
				'offset' => $params['offset'] ?? 0,
			]));
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
				'offset' => $params['offset'] ?? 0,
			]));
	}
}
