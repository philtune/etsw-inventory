<?php

use App\Models\EtsyListing;
use App\Models\EtsyReceipt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('etsy_transactions', function (Blueprint $table) {
			$table->unsignedBigInteger('id')
			      ->primary();
			$table->dateTime('created_at');
			$table->foreignIdFor(EtsyReceipt::class)
			      ->cascadeConstrained();
			$table->foreignIdFor(EtsyListing::class)
			      ->nullable();
			$table->string('variation', 64)
			      ->nullable();
			//			$table->string('title')
			//			      ->nullable();
			//			$table->text('description')
			//			      ->nullable();
			//			$table->unsignedBigInteger('seller_user_id')
			//			      ->nullable();
			//			$table->unsignedBigInteger('buyer_user_id')
			//			      ->nullable();
			$table->unsignedSmallInteger('quantity');
			$table->unsignedBigInteger('listing_image_id')
			      ->nullable();
			//			$table->boolean('is_digital')
			//			      ->default(false);
			//			$table->string('file_data')
			//			      ->nullable();
			//			$table->string('sku')
			//			      ->nullable();
			//			$table->unsignedBigInteger('product_id')
			//			      ->nullable();
			//			$table->string('transaction_type')
			//			      ->nullable();
			$table->decimal('price');
			//			$table->json('shipping_cost');
			$table->json('variations');
			//			$table->json('product_data')
			//			      ->nullable();
			//			$table->unsignedBigInteger('shipping_profile_id')
			//			      ->nullable();
			//			$table->unsignedTinyInteger('min_processing_days')
			//			      ->nullable();
			//			$table->unsignedTinyInteger('max_processing_days')
			//			      ->nullable();
			//			$table->string('shipping_method')
			//			      ->nullable();
			//			$table->string('shipping_upgrade')
			//			      ->nullable();
			//			$table->float('buyer_coupon')
			//			      ->default(0);
			$table->float('shop_coupon')
			      ->default(0);
			//			$table->dateTime('paid_at')
			//			      ->nullable();
			//			$table->dateTime('shipped_at')
			//			      ->nullable();
			//			$table->dateTime('expected_ship_at')
			//			      ->nullable();
			$table->json('meta')
			      ->nullable();
		});
	}

	public function down():void
	{
		Schema::dropIfExists('etsy_transactions');
	}
};
