<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('wholesale_customers', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->string('name')
			      ->nullable();
			$table->string('primary_address')
			      ->nullable();
			$table->string('phone_numbers')
			      ->nullable();
			$table->string('notes', 1024)
			      ->nullable();

			$table->datetimes();
			$table->softDeletesDatetime();
		});
	}
};
