<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('bundle_products', function (Blueprint $table) {
			$table->foreignIdFor(Product::class, 'parent_product_id')
			      ->constrained('products')
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->foreignIdFor(Product::class, 'child_product_id')
			      ->constrained('products')
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->string('variant', 64)
			      ->nullable();
			$table->unique(['parent_product_id', 'child_product_id', 'variant'], 'parent_child');
		});
	}

	public function down():void
	{
		Schema::dropIfExists('bundle_products');
	}
};
