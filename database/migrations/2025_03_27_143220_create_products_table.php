<?php

use App\Models\ProductType;
use App\Models\Scent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->datetimes();
			$table->softDeletesDatetime();
			$table->boolean('is_bundle')
			      ->default(false);
			$table->foreignIdFor(ProductType::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->foreignIdFor(Scent::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->string('label');
			$table->boolean('can_stock')
			      ->default(true);
			$table->string('stock')
			      ->default('{}');
			$table->boolean('is_archived')
			      ->default(false);
		});
	}

	public function down():void
	{
		Schema::dropIfExists('products');
	}
};
