<?php

use App\Models\OauthToken;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('oauth_tokens', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->enum('client_enum', OauthToken::clients)
			      ->unique();
			$table->string('state', 40)
			      ->nullable();
			$table->string('access_token')
			      ->nullable();
			$table->string('refresh_token')
			      ->nullable();
			$table->datetime('expires_at')
			      ->nullable();
			$table->unsignedMediumInteger('remaining_today')
			      ->nullable();
			$table->unsignedSmallInteger('remaining_this_second')
			      ->nullable();
			$table->dateTime('last_used_at')
			      ->nullable();
		});
	}
};
