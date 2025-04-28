<?php

use App\Models\Listing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::table('listings', function (Blueprint $table) {
			$table->renameColumn('state', 'state_enum');
		});
	}
};
