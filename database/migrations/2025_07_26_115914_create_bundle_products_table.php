<?php

use App\Models\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('bundle_product_types', function (Blueprint $table) {
			$table->foreignIdFor(ProductType::class, 'parent_product_type_id')
			      ->constrained('product_types')
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->foreignIdFor(ProductType::class, 'child_product_type_id')
			      ->constrained('product_types')
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->unique(['parent_product_type_id', 'child_product_type_id'], 'parent_child');
		});
	}

	public function down():void
	{
		Schema::dropIfExists('bundle_product_types');
	}
};
