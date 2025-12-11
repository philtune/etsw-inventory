<?php

namespace App\Services;

use App\Models\OauthToken;
use Closure;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Validator;

class EtsyApi
{

	/**
	 * @param Closure():Response $callback
	 */
	protected static function send(Closure $callback):array
	{
		$etsyOauthToken = OauthToken::getEtsyToken();
		if ( $etsyOauthToken->last_used_at?->isAfter(now()->subDay()) ) {
			if ( $etsyOauthToken->remaining_today === 0 ) {
				throw new TooManyRequestsHttpException(3600, 'Proxy tried too many times. Try again later.');
			}
			if ( $etsyOauthToken->remaining_this_second === 0 ) {
				sleep(1);
			}
		}
		$pendingRequest = Http
			::baseUrl('https://openapi.etsy.com/v3/application')
			->withToken(EtsyAuthService::getAccessToken())
			->withHeaders([
				'x-api-key' => config('services.etsy.api.key') . ':' . config('services.etsy.api_secret'),
			]);
		$response = $callback($pendingRequest);
		$etsyOauthToken->update([
			'remaining_today'       => $response->getHeader('X-Remaining-Today')[0],
			'remaining_this_second' => $response->getHeader('X-Remaining-This-Second')[0],
			'last_used_at'          => now()
		]);
		return $response->json();
	}

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getListing
	 * @throws ConnectionException
	 */
	public static function getListing(string $listing_id):array
	{
		return self::send(fn(PendingRequest $request) => $request
			->get('/listings/' . $listing_id, [
				'includes' => 'Inventory,Images'
			]));
	}

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getListingsByShop
	 * @throws ConnectionException
	 */
	public static function getListings(array $params = []):array
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
				'limit'    => $params['limit'] ?? 100,
				'offset'   => $params['offset'] ?? 0,
				'state'    => $params['state'] ?? 'active',
				'includes' => $params['includes'] ?? '',
			]));
	}

	/**
	 * @throws ConnectionException
	 */
	public static function getListingsByIds(array $listing_ids, array $params = []):array
	{
		return self::send(fn(PendingRequest $request) => $request
			->get('/listings/batch', [
					'listing_ids' => $listing_ids,
				] + $params));
	}

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getShopReceipts
	 * @throws ConnectionException
	 */
	public static function getReceipts(array $params = []):array
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
	 * @see https://developers.etsy.com/documentation/reference/#operation/getShopReceipt
	 * @throws ConnectionException
	 */
	public static function getReceipt(string $receipt_id):array
	{
		return self::send(fn(PendingRequest $request) => $request
			->withUrlParameters([
				'shop_id'    => config('services.etsy.shop_id'),
				'receipt_id' => $receipt_id,
			])
			->get('/shops/{shop_id}/receipts/{receipt_id}'));
	}

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getShopReceiptTransactionsByShop
	 * @throws ConnectionException
	 */
	public static function getTransactions(array $params = []):array
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

	/**
	 * @see https://developers.etsy.com/documentation/reference/#operation/getListingInventory
	 * @throws ConnectionException
	 */
	public static function getListingInventory(string $listing_id):array
	{
		return self::send(fn(PendingRequest $request) => $request
			->get('/listings/' . $listing_id . '/inventory'));
	}

	/**
	 * @param array{
	 *     products:array,
	 *     price_on_property:array,
	 *     quantity_on_property:array,
	 *     sku_on_property:array,
	 *     readiness_state_on_property:array
	 * } $inventory
	 * @throws ConnectionException
	 */
	public static function updateListingInventory(string $listing_id, array $inventory):array
	{
		return self::send(fn(PendingRequest $request) => $request
			->withUrlParameters(['listing_id' => $listing_id])
			->put('/listings/{listing_id}/inventory', $inventory));
	}
}
