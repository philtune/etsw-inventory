<?php

use App\Models\Product;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderLineItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('wholesale_order_line_items', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();

			$table->foreignIdFor(WholesaleOrder::class)
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->cascadeOnDelete();
			$table->foreignIdFor(Product::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->json('variation')
			      ->nullable();
			$table->unsignedMediumInteger('quantity');
			$table->decimal('price_per_unit')
			      ->default(0);
			$table->decimal('total_adjustment')
			      ->default(0);
			$table->string('notes', 1024)
			      ->nullable();
			$table->enum('status_enum', array_keys(WholesaleOrderLineItem::status_options))
			      ->default(WholesaleOrderLineItem::STATUS_TODO);
			$table->dateTime('ordered_at')
			      ->useCurrent();
			$table->dateTime('started_at')
			      ->nullable();
			$table->dateTime('completed_at')
			      ->nullable();
			$table->dateTime('packed_at')
			      ->nullable();

			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}
};
