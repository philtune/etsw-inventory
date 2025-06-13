<?php

use App\Models\EtsyReceipt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('etsy_receipts', function (Blueprint $table) {
			$table->unsignedBigInteger('id')
			      ->primary();
			// The numeric value for the Etsy channel that serviced the purchase: 0 for Etsy.com, 1 for a Pattern shop.
			$table->unsignedTinyInteger('receipt_type')
			      ->default(EtsyReceipt::TYPE_ETSY_COM);
			$table->unsignedBigInteger('seller_user_id');
			$table->string('seller_email');
			$table->unsignedBigInteger('buyer_user_id');
			$table->string('buyer_email')
			      ->nullable();
			$table->string('name');
			$table->string('first_line');
			$table->string('second_line')
			      ->nullable();
			$table->string('city');
			$table->string('state');
			$table->string('zip');
			$table->string('status');
			$table->string('formatted_address');
			$table->string('country_iso');
			$table->string('payment_method');
			$table->string('payment_email')
			      ->nullable();
			$table->string('message_from_payment')
			      ->nullable();
			$table->text('message_from_seller')
			      ->nullable();
			$table->text('message_from_buyer')
			      ->nullable();
			$table->boolean('is_shipped');
			$table->boolean('is_paid');
			$table->boolean('is_gift');
			$table->string('gift_message');
			$table->string('gift_sender');
			$table->json('grandtotal');
			$table->json('subtotal');
			$table->json('total_price');
			$table->json('total_shipping_cost');
			$table->json('total_tax_cost');
			$table->json('total_vat_cost');
			$table->json('discount_amt');
			$table->json('gift_wrap_price');
			$table->json('shipments');
			$table->json('refunds');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		});
	}

	public function down():void
	{
		Schema::dropIfExists('etsy_receipts');
	}
};
