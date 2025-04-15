<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Receipt;
use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;

class DataImportService
{
	/**
	 * @throws ConnectionException
	 */
	public static function importListings():void
	{
		$offset = 0;
		do {
			$response = EtsyApplicationApi::listings(['limit' => 100, 'offset' => $offset]);
			$count = $response['count'];
			foreach ( $response['results'] as $row ) {
				Listing::updateOrCreate(['id' => $row['listing_id']],
					[
						'user_id'                        => $row['user_id'],
						'shop_id'                        => $row['shop_id'],
						'shop_section_id'                => $row['shop_section_id'],
						'title'                          => $row['title'],
						'description'                    => $row['description'],
						'state'                          => $row['state'],
						'creation_at'                    => $row['creation_timestamp'],
						'created_at'                     => $row['created_timestamp'],
						'ending_at'                      => $row['ending_timestamp'],
						'original_creation_at'           => $row['original_creation_timestamp'],
						'last_modified_at'               => $row['last_modified_timestamp'],
						'updated_at'                     => $row['updated_timestamp'],
						'state_at'                       => $row['state_timestamp'],
						'quantity'                       => $row['quantity'],
						'featured_rank'                  => $row['featured_rank'],
						'url'                            => $row['url'],
						'num_favorers'                   => $row['num_favorers'],
						'non_taxable'                    => $row['non_taxable'],
						'is_taxable'                     => $row['is_taxable'],
						'is_customizable'                => $row['is_customizable'],
						'is_personalizable'              => $row['is_personalizable'],
						'personalization_is_required'    => $row['personalization_is_required'],
						'personalization_char_count_max' => $row['personalization_char_count_max'],
						'personalization_instructions'   => $row['personalization_instructions'],
						'listing_type'                   => $row['listing_type'],
						'tags'                           => $row['tags'],
						'materials'                      => $row['materials'],
						'shipping_profile_id'            => $row['shipping_profile_id'],
						'return_policy_id'               => $row['return_policy_id'],
						'processing_min'                 => $row['processing_min'],
						'processing_max'                 => $row['processing_max'],
						'who_made'                       => $row['who_made'],
						'when_made'                      => $row['when_made'],
						'is_supply'                      => $row['is_supply'],
						'item_weight'                    => $row['item_weight'],
						'item_weight_unit'               => $row['item_weight_unit'],
						'item_length'                    => $row['item_length'],
						'item_width'                     => $row['item_width'],
						'item_height'                    => $row['item_height'],
						'item_dimensions_unit'           => $row['item_dimensions_unit'],
						'is_private'                     => $row['is_private'],
						'style'                          => $row['style'],
						'file_data'                      => $row['file_data'],
						'has_variations'                 => $row['has_variations'],
						'should_auto_renew'              => $row['should_auto_renew'],
						'language'                       => $row['language'],
						'price'                          => $row['price'],
						'taxonomy_id'                    => $row['taxonomy_id'],
						'production_partners'            => $row['production_partners'],
						'skus'                           => $row['skus'],
						'views'                          => $row['views'],
						'shipping_profile'               => $row['shipping_profile'] ?: (object) [],
						'shop'                           => $row['shop'] ?: (object) [],
						'images'                         => $row['images'] ?: [],
						'videos'                         => $row['videos'] ?: [],
						'user'                           => $row['user'] ?: (object) [],
						'translations'                   => $row['translations'] ?: (object) [],
						'inventory'                      => $row['inventory'] ?: (object) [],
					]);
			}
			$offset += 100;
		} while ( $offset < $count );
	}

	/**
	 * @throws ConnectionException
	 */
	public function importReceipts():void
	{
		$latest = Receipt::latest()->first();
		$offset = 0;
		do {
			$response = EtsyApplicationApi::receipts(['limit' => 100, 'offset' => $offset]);
			$count = $response['count'];
			foreach ( $response['results'] as $row ) {
				Receipt::updateOrCreate(['id' => $row['receipt_id']],
					[
						'receipt_type'         => strval($row['receipt_type']),
						'seller_user_id'       => $row['seller_user_id'],
						'seller_email'         => $row['seller_email'],
						'buyer_user_id'        => $row['buyer_user_id'],
						'buyer_email'          => $row['buyer_email'],
						'name'                 => $row['name'],
						'first_line'           => $row['first_line'],
						'second_line'          => $row['second_line'],
						'city'                 => $row['city'],
						'state'                => $row['state'],
						'zip'                  => $row['zip'],
						'status'               => $row['status'],
						'formatted_address'    => $row['formatted_address'],
						'country_iso'          => $row['country_iso'],
						'payment_method'       => $row['payment_method'],
						'payment_email'        => $row['payment_email'],
						'message_from_payment' => $row['message_from_payment'],
						'message_from_seller'  => $row['message_from_seller'],
						'message_from_buyer'   => $row['message_from_buyer'],
						'is_shipped'           => $row['is_shipped'],
						'is_paid'              => $row['is_paid'],
						'is_gift'              => $row['is_gift'],
						'gift_message'         => $row['gift_message'],
						'gift_sender'          => $row['gift_sender'],
						'grandtotal'           => $row['grandtotal'],
						'subtotal'             => $row['subtotal'],
						'total_price'          => $row['total_price'],
						'total_shipping_cost'  => $row['total_shipping_cost'],
						'total_tax_cost'       => $row['total_tax_cost'],
						'total_vat_cost'       => $row['total_vat_cost'],
						'discount_amt'         => $row['discount_amt'],
						'gift_wrap_price'      => $row['gift_wrap_price'],
						'shipments'            => $row['shipments'],
						'refunds'              => $row['refunds'],
						'created_at'           => $row['created_timestamp'],
						'updated_at'           => $row['updated_timestamp'],
					]);
			}
			$offset += 100;
		} while ( Receipt::count() < $count && $latest && ( $row['id'] ?? 0 ) > $latest->id );
	}

	/**
	 * @throws ConnectionException
	 */
	public function importTransactions():void
	{
		$latest = Transaction::latest()->first();
		$offset = 0;
		do {
			$response = EtsyApplicationApi::transactions(['limit' => 100, 'offset' => $offset]);
			$count = $response['count'];
			foreach ( $response['results'] as $row ) {
				Transaction::updateOrCreate(['id' => $row['transaction_id']],
					[
						'title'               => $row['title'],
						'description'         => $row['description'],
						'seller_user_id'      => $row['seller_user_id'],
						'buyer_user_id'       => $row['buyer_user_id'],
						'quantity'            => $row['quantity'],
						'listing_image_id'    => $row['listing_image_id'],
						'receipt_id'          => $row['receipt_id'],
						'is_digital'          => $row['is_digital'],
						'file_data'           => $row['file_data'],
						'listing_id'          => $row['listing_id'],
						'sku'                 => $row['sku'],
						'product_id'          => $row['product_id'],
						'transaction_type'    => $row['transaction_type'],
						'price'               => $row['price'],
						'shipping_cost'       => $row['shipping_cost'],
						'variations'          => $row['variations'],
						'product_data'        => $row['product_data'],
						'shipping_profile_id' => $row['shipping_profile_id'],
						'min_processing_days' => $row['min_processing_days'],
						'max_processing_days' => $row['max_processing_days'],
						'shipping_method'     => $row['shipping_method'],
						'shipping_upgrade'    => $row['shipping_upgrade'],
						'buyer_coupon'        => $row['buyer_coupon'],
						'shop_coupon'         => $row['shop_coupon'],
						'created_at'          => $row['created_timestamp'],
						'paid_at'             => $row['paid_timestamp'],
						'shipped_at'          => $row['shipped_timestamp'],
						'expected_ship_at'    => $row['expected_ship_date'],
					]);
			}
			$offset += 100;
		} while ( Transaction::count() < $count && ( $row['id'] ?? 0 ) > $latest->id );
	}

}
