<?php

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
			$table->boolean('is_bundle')
			      ->default(false);
			$table->string('code', 16)
			      ->unique();
			$table->string('label')
			      ->unique();
			$table->string('variants')
			      ->nullable();
			$table->unsignedInteger('etsy_section_id')
			      ->nullable();
		});
	}

	public function down():void
	{
		Schema::dropIfExists('product_types');
	}
};
