<?php

use App\Models\EtsyListing;
use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('seed_products', function () {
	EtsyListing::each(function (EtsyListing $etsyListing) {
		$product = Product::create([
			'product_type_id' => $etsyListing->product_type_id,
			'scent_id'        => $etsyListing->scent_id,
			'label'           => $etsyListing->title,
			'is_archived'     => $etsyListing->is_archived,
		]);
		$etsyListing->product()->associate($product);
		$etsyListing->save();
	});
});
