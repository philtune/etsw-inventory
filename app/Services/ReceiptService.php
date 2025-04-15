<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\Transaction;
use Illuminate\Http\Client\ConnectionException;

class ReceiptService
{

	/**
	 * @throws ConnectionException
	 */
	public static function import():void
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

}
