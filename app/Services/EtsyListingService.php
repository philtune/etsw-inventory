<?php

namespace App\Services;

use App\Models\EtsyListing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;

class EtsyListingService
{

	/**
	 * @throws ConnectionException
	 */
	public static function importState(string $state = 'active'):void
	{
		$offset = 0;
		do {
			$response = EtsyApi::getListings([
				'limit'    => 100,
				'offset'   => $offset,
				'state'    => $state,
				'includes' => 'Inventory,Images',
			]);
			$count = $response['count'];
			foreach ( $response['results'] as $row ) {
				EtsyListing::updateOrCreate(['id' => $row['listing_id']], self::map($row));
			}
			$offset += 100;
		} while ( $offset < $count );
	}

	public static function import(string $listing_id):array|false
	{
		try {
			$row = EtsyApi::getListing($listing_id);
			return self::map($row);
		} catch ( ConnectionException $e ) {
			return false;
		}
	}

	/**
	 * @throws ConnectionException
	 */
	public static function importAllInventory():void
	{
		EtsyListing
			::query()
			->whereHas('product', callback: fn(Builder $query) => $query->where('is_archived', 0))
			->pluck('id')
			->chunk(100)
			->each(function (Collection $listing_ids) {
				$response = EtsyApi::getListingsByIds($listing_ids->toArray(), [
					'includes' => 'Inventory,Images',
				]);
				foreach ( $response['results'] as $row ) {
					EtsyListing::where('id', $row['listing_id'])->update(self::map($row));
				}
			});
	}

	public static function getInventory(string $listing_id):array|false
	{
		try {
			return EtsyApi::getListingInventory($listing_id);
		} catch ( ConnectionException $e ) {
			return false;
		}
	}

	public static function updateInventory(string $listing_id, array $inventory):array|false
	{
		try {
			return EtsyApi::updateListingInventory($listing_id, $inventory);
		} catch ( ConnectionException $e ) {
			return false;
		}
	}

	/**
	 * @throws ConnectionException
	 */
	public static function importAll():void
	{
		static::importState('active');
		static::importState('inactive');
		static::importState('sold_out');
		static::importState('expired');
	}

	private static function map($row):array
	{
		return [
			'title'      => $row['title'],
			'state_enum' => $row['state'],
			//						'price'      => $row['price'],
			//						'quantity'   => $row['quantity'],
			'inventory'  => $row['inventory'],
			'url'        => $row['url'],
			'thumbnail'  => $row['images'][0]['url_75x75'] ?? null,
			'meta'       => $row,
			'created_at' => Carbon::createFromTimestamp($row['original_creation_timestamp']),
			//			'updated_at'        => Carbon::createFromTimestamp($row['updated_timestamp']),
			'ending_at'  => Carbon::createFromTimestamp($row['ending_timestamp']),
			//							[
			//							'id'                     => $row['listing_id'],
			//							'title'                          => $row['title'],
			//							'description'                    => $row['description'],
			//							'state'                          => $row['state'],
			//							'shop_section_id'                => $row['shop_section_id'],
			//							'quantity'                       => $row['quantity'],
			//							'featured_rank'                  => $row['featured_rank'],
			//							'url'                            => $row['url'],
			//							'num_favorers'                   => $row['num_favorers'],
			//							'non_taxable'                    => $row['non_taxable'],
			//							'is_taxable'                     => $row['is_taxable'],
			//							'is_customizable'                => $row['is_customizable'],
			//							'is_personalizable'              => $row['is_personalizable'],
			//							'personalization_is_required'    => $row['personalization_is_required'],
			//							'personalization_char_count_max' => $row['personalization_char_count_max'],
			//							'personalization_instructions'   => $row['personalization_instructions'],
			//							'listing_type'                   => $row['listing_type'],
			//							'tags'                           => $row['tags'],
			//							'materials'                      => $row['materials'],
			//							'shipping_profile_id'            => $row['shipping_profile_id'],
			//							'return_policy_id'               => $row['return_policy_id'],
			//							'processing_min'                 => $row['processing_min'],
			//							'processing_max'                 => $row['processing_max'],
			//							'who_made'                       => $row['who_made'],
			//							'when_made'                      => $row['when_made'],
			//							'is_supply'                      => $row['is_supply'],
			//							'item_weight'                    => $row['item_weight'],
			//							'item_weight_unit'               => $row['item_weight_unit'],
			//							'item_length'                    => $row['item_length'],
			//							'item_width'                     => $row['item_width'],
			//							'item_height'                    => $row['item_height'],
			//							'item_dimensions_unit'           => $row['item_dimensions_unit'],
			//							'is_private'                     => $row['is_private'],
			//							'style'                          => $row['style'],
			//							'file_data'                      => $row['file_data'],
			//							'has_variations'                 => $row['has_variations'],
			//							'should_auto_renew'              => $row['should_auto_renew'],
			//							'language'                       => $row['language'],
			//							'price'                          => $row['price'],
			//							'taxonomy_id'                    => $row['taxonomy_id'],
			//							'production_partners'            => $row['production_partners'],
			//							'skus'                           => $row['skus'],
			//							'views'                          => $row['views'],
			//							'shipping_profile'               => $row['shipping_profile'] ?: (object) [],
			//							'shop'                           => $row['shop'] ?: (object) [],
			//							'images'                         => $row['images'] ?: [],
			//							'videos'                         => $row['videos'] ?: [],
			//							'user'                           => $row['user'] ?: (object) [],
			//							'translations'                   => $row['translations'] ?: (object) [],
			//							'inventory'                      => $row['inventory'] ?: (object) [],
			//							'user_id'                        => $row['user_id'],
			//							'shop_id'                        => $row['shop_id'],
			//							'original_creation_at'           => $row['original_creation_timestamp'],
			//							'creation_at'                    => $row['creation_timestamp'],
			//							'created_at'                     => $row['created_timestamp'],
			//							'last_modified_at'               => $row['last_modified_timestamp'],
			//							'updated_at'                     => $row['updated_timestamp'],
			//							'state_at'                       => $row['state_timestamp'],
			//						]
		];
	}

}
