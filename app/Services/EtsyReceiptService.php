<?php

namespace App\Services;

use App\Models\EtsyReceipt;
use App\Models\EtsyTransaction;
use Illuminate\Http\Client\ConnectionException;

class EtsyReceiptService
{

	/**
	 * @throws ConnectionException
	 */
	public static function import():void
	{
		$offset = 0;
		do {
			$response = EtsyApplicationApi::getReceipts(['limit' => 100, 'offset' => $offset]);
			$count = $response['count'];
			foreach ( $response['results'] as $receipt ) {
				EtsyReceipt::firstOrCreate(['id' => $receipt['receipt_id']], static::mapReceipt($receipt));
				foreach ( $receipt['transactions'] as $transaction ) {
					EtsyTransaction::firstOrCreate(['id' => $transaction['transaction_id']], static::mapTransaction($transaction));
				}
			}
			$offset += 100;
		} while (
			EtsyReceipt::count() < $count &&
			( $receipt['created_timestamp'] ?? 0 ) > ( $latest?->created_at->timestamp ?: 0 )
		);
	}

	/**
	 * @throws ConnectionException
	 */
	public static function importReceipt(string $receipt_id):void
	{
		EtsyReceipt::firstOrCreate(['id' => $receipt_id], static::mapReceipt(EtsyApplicationApi::getReceipt($receipt_id)));
	}

	private static function mapReceipt(array $receipt):array
	{
		return [
			'created_at'         => $receipt['created_timestamp'],
			'updated_at'         => $receipt['updated_timestamp'],
			'buyer_user_id'      => $receipt['buyer_user_id'],
			'subtotal'           => $receipt['subtotal']['amount'] / $receipt['subtotal']['divisor'],
			'name'               => $receipt['name'],
			'city'               => $receipt['city'],
			'state'              => $receipt['state'],
			'status'             => $receipt['status'],
			'message_from_buyer' => $receipt['message_from_buyer'],
			'is_gift'            => $receipt['is_gift'],
			'meta'               => [
				//				'receipt_type'         => strval($receipt['receipt_type']),
				//				'seller_user_id'       => $receipt['seller_user_id'],
				//				'seller_email'         => $receipt['seller_email'],
				//				'buyer_email'          => $receipt['buyer_email'],
				'first_line'           => $receipt['first_line'],
				'second_line'          => $receipt['second_line'],
				'zip'                  => $receipt['zip'],
				'formatted_address'    => $receipt['formatted_address'],
				'country_iso'          => $receipt['country_iso'],
				'payment_method'       => $receipt['payment_method'],
				'payment_email'        => $receipt['payment_email'],
				'message_from_payment' => $receipt['message_from_payment'],
				'message_from_seller'  => $receipt['message_from_seller'],
				'gift_message'         => $receipt['gift_message'],
				'gift_sender'          => $receipt['gift_sender'],
				'grandtotal'           => $receipt['grandtotal']['amount'] / $receipt['grandtotal']['divisor'],
				'total_price'          => $receipt['total_price']['amount'] / $receipt['total_price']['divisor'],
				//			'total_shipping_cost' => $receipt['total_shipping_cost']['amount'] / $receipt['total_shipping_cost']['divisor'],
				'total_tax_cost'       => $receipt['total_tax_cost']['amount'] / $receipt['total_tax_cost']['divisor'],
				//			'total_vat_cost'      => $receipt['total_vat_cost']['amount'] / $receipt['total_vat_cost']['divisor'],
				'discount_amt'         => $receipt['discount_amt']['amount'] / $receipt['discount_amt']['divisor'],
				//			'gift_wrap_price'     => $receipt['gift_wrap_price']['amount'] / $receipt['gift_wrap_price']['divisor'],
				'shipments'            => $receipt['shipments'],
				'refunds'              => $receipt['refunds'],
			]
		];
	}

	private static function mapTransaction(array $transaction):array
	{
		return [
			'created_at'       => $transaction['created_timestamp'],
			'etsy_receipt_id'  => $transaction['receipt_id'],
			'etsy_listing_id'  => $transaction['listing_id'],
			'variation'        => EtsyTransaction::getVariation($transaction['listing_id'], json_encode($transaction['variations'])),
			//			'seller_user_id'   => $row['seller_user_id'],
			'quantity'         => $transaction['quantity'],
			'listing_image_id' => $transaction['listing_image_id'],
			//			'is_digital'       => $row['is_digital'],
			//			'file_data'        => $row['file_data'],
			//			'sku'              => $row['sku'],
			//			'transaction_type' => $row['transaction_type'],
			'price'            => $transaction['price']['amount'] / $transaction['price']['divisor'],
			//			'shipping_cost'    => $row['shipping_cost'],
			'variations'       => $transaction['variations'],
			//			'shipping_method'     => $row['shipping_method'],
			//			'shipping_upgrade'    => $row['shipping_upgrade'],
			'shop_coupon'      => $transaction['shop_coupon'],
			'meta'             => [
				'title'               => $transaction['title'],
				'description'         => $transaction['description'],
				'buyer_user_id'       => $transaction['buyer_user_id'],
				'product_id'          => $transaction['product_id'],
				'product_data'        => $transaction['product_data'],
				'shipping_profile_id' => $transaction['shipping_profile_id'],
				'min_processing_days' => $transaction['min_processing_days'],
				'max_processing_days' => $transaction['max_processing_days'],
				'buyer_coupon'        => $transaction['buyer_coupon'],
				'paid_at'             => $transaction['paid_timestamp'],
				'shipped_at'          => $transaction['shipped_timestamp'],
				'expected_ship_at'    => $transaction['expected_ship_date'],
			]
		];
	}

}
