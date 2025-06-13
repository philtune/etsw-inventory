<?php

use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('wholesale_orders', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->foreignIdFor(WholesaleCustomer::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->decimal('subtotal')
			      ->default(0);
			$table->decimal('total_adjustment')
			      ->default(0);
			$table->decimal('amount_paid')
			      ->default(0);
			$table->string('notes', 1024)
			      ->nullable();
			$table->enum('status_enum', array_keys(WholesaleOrder::status_options))
			      ->default(WholesaleOrder::STATUS_ORDERED);
			$table->dateTime('ordered_at')
			      ->useCurrent();
			$table->dateTime('started_at')
			      ->nullable();
			$table->dateTime('completed_at')
			      ->nullable();
			$table->dateTime('shipped_at')
			      ->nullable();
			$table->dateTime('pif_at')
			      ->nullable();
			$table->enum('delivery_method_enum', array_keys(WholesaleOrder::delivery_method_options))
			      ->default(WholesaleOrder::DELIVERY_METHOD_SHIPMENT);

			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}
};
