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
			$table->unsignedInteger('section_id')
			      ->nullable();
			$table->string('code', 8)
			      ->unique();
			$table->string('label')
			      ->unique();
			$table->json('meta')
			      ->nullable();
			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}

	public function down():void
	{
		Schema::dropIfExists('product_types');
	}
};
