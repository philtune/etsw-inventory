<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;

class TransactionService
{

	/**
	 * @throws ConnectionException
	 */
	public static function import():void
	{
		$latest = Transaction::latest()->first();
		$offset = 0;
		do {
			$response = EtsyApplicationApi::getTransactions(['limit' => 100, 'offset' => $offset]);
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
