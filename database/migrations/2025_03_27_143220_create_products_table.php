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
			$table->string('label');
			$table->boolean('is_bundle')
			      ->default(false);
			$table->foreignIdFor(ProductType::class)
			      ->nullConstrained();
			$table->foreignIdFor(Scent::class)
			      ->nullConstrained();
			$table->boolean('is_made_to_order')
			      ->default(false);
			$table->unsignedSmallInteger('stock')
			      ->default(0);
			$table->dateTime('stock_updated_at')
			      ->nullable();
			$table->dateTime('snooze_until')
			      ->nullable();
			$table->boolean('is_archived')
			      ->default(false);
			$table->string('notes', 1024)
			      ->nullable();
		});
	}

	public function down():void
	{
		Schema::dropIfExists('products');
	}
};
