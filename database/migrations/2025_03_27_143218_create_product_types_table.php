<?php

use App\Models\ProductTypeVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('product_types', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->datetimes();
			$table->softDeletesDatetime();
			$table->string('label');
			$table->string('code', 16);
			$table->string('variant_label', 16)
			      ->nullable();
			$table->foreignIdFor(ProductTypeVariant::class)
			      ->nullConstrained();
			$table->unsignedInteger('etsy_section_id')
			      ->nullable();
			$table->unique(['code', 'deleted_at']);
			$table->unique(['label', 'deleted_at']);
		});
	}

	public function down():void
	{
		Schema::dropIfExists('product_types');
	}
};
