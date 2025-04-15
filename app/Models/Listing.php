<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'creation_at'          => 'datetime',
		'created_at'           => 'datetime',
		'ending_at'            => 'datetime',
		'original_creation_at' => 'datetime',
		'last_modified_at'     => 'datetime',
		'updated_at'           => 'datetime',
		'state_at'             => 'datetime',
		'tags'                 => 'json',
		'materials'            => 'json',
		'style'                => 'json',
		'price'                => 'json',
		'production_partners'  => 'json',
		'skus'                 => 'json',
		'shipping_profile'     => 'json',
		'shop'                 => 'json',
		'images'               => 'json',
		'videos'               => 'json',
		'user'                 => 'json',
		'translations'         => 'json',
		'inventory'            => 'json',
	];
	const CREATED_AT = 'original_creation_at';

	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'edit';
	const STATE_SOLD_OUT = 'sold_out';
	const STATE_DRAFT = 'draft';
	const STATE_EXPIRED = 'expired';
	const states = [
		self::STATE_ACTIVE,
		self::STATE_INACTIVE,
		self::STATE_SOLD_OUT,
		self::STATE_DRAFT,
		self::STATE_EXPIRED,
	];

	const TYPE_PHYSICAL = 'physical';
	const TYPE_DOWNLOAD = 'download';
	const TYPE_BOTH = 'both';
	const types = [
		self::TYPE_PHYSICAL,
		self::TYPE_DOWNLOAD,
		self::TYPE_BOTH,
	];

	const WHO_MADE_I_DID = 'i_did';
	const WHO_MADE_SOMEONE_ELSE = 'someone_else';
	const WHO_MADE_COLLECTIVE = 'collective';
	const who_mades = [
		self::WHO_MADE_I_DID,
		self::WHO_MADE_SOMEONE_ELSE,
		self::WHO_MADE_COLLECTIVE,
	];

	const WHEN_MADE_MADE_TO_ORDER = 'made_to_order';
	const WHEN_MADE_2020_2025 = '2020_2025';
	const WHEN_MADE_2010_2019 = '2010_2019';
	const WHEN_MADE_2006_2009 = '2006_2009';
	const WHEN_MADE_BEFORE_2006 = 'before_2006';
	const WHEN_MADE_2000_2005 = '2000_2005';
	const WHEN_MADE_1990S = '1990s';
	const WHEN_MADE_1980S = '1980s';
	const WHEN_MADE_1970S = '1970s';
	const WHEN_MADE_1960S = '1960s';
	const WHEN_MADE_1950S = '1950s';
	const WHEN_MADE_1940S = '1940s';
	const WHEN_MADE_1930S = '1930s';
	const WHEN_MADE_1920S = '1920s';
	const WHEN_MADE_1910S = '1910s';
	const WHEN_MADE_1900S = '1900s';
	const WHEN_MADE_1800S = '1800s';
	const WHEN_MADE_1700S = '1700s';
	const WHEN_MADE_BEFORE_1700 = 'before_1700';
	const when_mades = [
		self::WHEN_MADE_MADE_TO_ORDER,
		self::WHEN_MADE_2020_2025,
		self::WHEN_MADE_2010_2019,
		self::WHEN_MADE_2006_2009,
		self::WHEN_MADE_BEFORE_2006,
		self::WHEN_MADE_2000_2005,
		self::WHEN_MADE_1990S,
		self::WHEN_MADE_1980S,
		self::WHEN_MADE_1970S,
		self::WHEN_MADE_1960S,
		self::WHEN_MADE_1950S,
		self::WHEN_MADE_1940S,
		self::WHEN_MADE_1930S,
		self::WHEN_MADE_1920S,
		self::WHEN_MADE_1910S,
		self::WHEN_MADE_1900S,
		self::WHEN_MADE_1800S,
		self::WHEN_MADE_1700S,
		self::WHEN_MADE_BEFORE_1700,
	];

	const ITEM_WEIGHT_UNIT_OZ = 'oz';
	const ITEM_WEIGHT_UNIT_LB = 'lb';
	const ITEM_WEIGHT_UNIT_G = 'g';
	const ITEM_WEIGHT_UNIT_kg = 'kg';
	const item_weight_units = [
		self::ITEM_WEIGHT_UNIT_OZ,
		self::ITEM_WEIGHT_UNIT_LB,
		self::ITEM_WEIGHT_UNIT_G,
		self::ITEM_WEIGHT_UNIT_kg,
	];

	const ITEM_DIMENSIONS_UNIT_IN = 'in';
	const ITEM_DIMENSIONS_UNIT_FT = 'ft';
	const ITEM_DIMENSIONS_UNIT_MM = 'mm';
	const ITEM_DIMENSIONS_UNIT_CM = 'cm';
	const ITEM_DIMENSIONS_UNIT_M = 'm';
	const ITEM_DIMENSIONS_UNIT_YD = 'yd';
	const ITEM_DIMENSIONS_UNIT_INCHES = 'inches';
	const item_dimensions_units = [
		self::ITEM_DIMENSIONS_UNIT_IN,
		self::ITEM_DIMENSIONS_UNIT_FT,
		self::ITEM_DIMENSIONS_UNIT_MM,
		self::ITEM_DIMENSIONS_UNIT_CM,
		self::ITEM_DIMENSIONS_UNIT_M,
		self::ITEM_DIMENSIONS_UNIT_YD,
		self::ITEM_DIMENSIONS_UNIT_INCHES,
	];
}
