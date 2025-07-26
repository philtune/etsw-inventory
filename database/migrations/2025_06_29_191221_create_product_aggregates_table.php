<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('product_aggregates', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->foreignIdFor(Product::class)
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->unsignedTinyInteger('etsy_listings_count')
			      ->nullable();
			$table->unsignedMediumInteger('etsy_transactions_qty')
			      ->nullable();
			$table->decimal('etsy_revenue')
			      ->nullable();
			$table->unsignedMediumInteger('wholesale_order_products_qty')
			      ->nullable();
			$table->decimal('wholesale_revenue')
			      ->nullable();
			$table->unsignedMediumInteger('total_qty')
			      ->nullable();
			$table->decimal('total_revenue')
			      ->nullable();
		});
	}
	public function down():void
	{
		Schema::dropIfExists('product_aggregates');
	}
};
