<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OauthToken extends Model
{
	use HasUuids;

	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'expires_at'   => 'datetime',
		'last_used_at' => 'datetime',
	];
	const CLIENT_ETSY = 'etsy';
	const clients = [
		self::CLIENT_ETSY,
	];

	public static function getEtsyToken():static
	{
		static $oauthToken = null;
		if ( is_null($oauthToken) ) {
			$oauthToken = OauthToken::firstOrCreate(['client_enum' => self::CLIENT_ETSY]);
		}
		return $oauthToken;
	}
}
