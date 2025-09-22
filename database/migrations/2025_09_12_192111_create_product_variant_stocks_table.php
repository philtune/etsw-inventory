<?php

use App\Models\Product;
use App\Models\ProductTypeVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('product_variants_stock', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->foreignIdFor(Product::class)
			      ->cascadeConstrained();
			$table->foreignIdFor(ProductTypeVariant::class)
			      ->cascadeConstrained();
			$table->unsignedSmallInteger('stock')
			      ->default(0);
			$table->dateTime('stock_updated_at')
			      ->nullable();
		});
	}

	public function down():void
	{
		Schema::dropIfExists('product_variants_stock');
	}
};
