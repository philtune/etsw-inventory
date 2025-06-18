<?php

use App\Models\WholesaleCustomer;
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
			$table->string('notes', 1024)
			      ->nullable();
			$table->date('ordered_at')
			      ->useCurrent();

			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}
};
