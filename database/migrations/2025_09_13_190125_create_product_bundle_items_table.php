<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('product_bundle_items', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->foreignUuid('product_id')
			      ->cascadeConstrained('products');
			$table->foreignUuid('child_product_id')
			      ->cascadeConstrained('products');
			$table->foreignUuid('product_type_variant_id')
			      ->nullConstrained();
		});
	}
};
