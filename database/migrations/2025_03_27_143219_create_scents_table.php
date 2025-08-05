<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('scents', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->datetimes();
			$table->softDeletesDatetime();
			$table->string('code', 16);
			$table->string('label');
			$table->unique(['code', 'deleted_at']);
			$table->unique(['label', 'deleted_at']);
		});
	}

	public function down():void
	{
		Schema::dropIfExists('scents');
	}
};
