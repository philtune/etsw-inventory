<?php

use App\Models\Product;
use App\Models\WholesaleOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('wholesale_order_products', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();

			$table->foreignIdFor(WholesaleOrder::class)
			      ->cascadeConstrained();
			$table->foreignIdFor(Product::class)
			      ->nullConstrained();
			$table->json('variation')
			      ->nullable();
			$table->unsignedMediumInteger('quantity')
			      ->default(0);
			$table->decimal('price_per_unit')
			      ->default(0);
			$table->decimal('total_adjustment')
			      ->default(0);
			$table->string('notes', 1024)
			      ->nullable();
			$table->unsignedTinyInteger('position')
			      ->default(1);

			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}
};
