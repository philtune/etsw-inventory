<?php

use App\Models\Listing;
use App\Models\ProductType;
use App\Models\Scent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up():void
	{
		Schema::create('listings', function (Blueprint $table) {
			$table->uuid('id')
			      ->primary();
			$table->unsignedBigInteger('listing_id');
			$table->foreignIdFor(ProductType::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->foreignIdFor(Scent::class)
			      ->nullable()
			      ->constrained()
			      ->cascadeOnUpdate()
			      ->nullOnDelete();
			$table->string('title');
			$table->enum('state_enum', array_keys(Listing::state_options));
			$table->boolean('is_archived')
			      ->default(false);
			$table->json('price');
			$table->unsignedSmallInteger('quantity');
			$table->string('url');
			$table->json('meta');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
			$table->dateTime('ending_at');
			//			$table->unsignedBigIntpeger('shop_section_id')
			//			      ->nullable();
			//			$table->text('description');
			//			$table->tinyInteger('featured_rank');
			//			$table->unsignedSmallInteger('num_favorers');
			//			$table->boolean('non_taxable');
			//			$table->boolean('is_taxable');
			//			$table->boolean('is_customizable');
			//			$table->boolean('is_personalizable');
			//			$table->boolean('personalization_is_required');
			//			$table->unsignedSmallInteger('personalization_char_count_max')
			//			      ->nullable();
			//			$table->text('personalization_instructions')
			//			      ->nullable();
			//			$table->enum('listing_type', Listing::types);
			//			$table->json('tags');
			//			$table->json('materials');
			//			$table->unsignedBigInteger('shipping_profile_id');
			//			$table->unsignedBigInteger('return_policy_id')
			//			      ->nullable();
			//			$table->unsignedTinyInteger('processing_min')
			//			      ->nullable();
			//			$table->unsignedTinyInteger('processing_max')
			//			      ->nullable();
			//			$table->enum('who_made', Listing::who_mades);
			//			$table->enum('when_made', Listing::when_mades);
			//			$table->boolean('is_supply');
			//			$table->float('item_weight')
			//			      ->nullable();
			//			$table->enum('item_weight_unit', Listing::item_weight_units)
			//			      ->nullable();
			//			$table->float('item_length')
			//			      ->nullable();
			//			$table->float('item_width')
			//			      ->nullable();
			//			$table->float('item_height')
			//			      ->nullable();
			//			$table->enum('item_dimensions_unit', Listing::item_dimensions_units)
			//			      ->nullable();
			//			$table->boolean('is_private');
			//			$table->json('style');
			//			$table->string('file_data');
			//			$table->boolean('has_variations');
			//			$table->boolean('should_auto_renew');
			//			$table->string('language', 5);
			//			$table->unsignedSmallInteger('taxonomy_id');
			//			$table->json('production_partners');
			//			$table->json('skus');
			//			$table->unsignedMediumInteger('views');
			//			$table->json('shipping_profile');
			//			$table->json('shop');
			//			$table->json('images');
			//			$table->json('videos');
			//			$table->json('user');
			//			$table->json('translations');
			//			$table->json('inventory');
			//			$table->unsignedBigInteger('user_id');
			//			$table->unsignedBigInteger('shop_id');
			//			$table->dateTime('original_creation_at');
			//			$table->dateTime('creation_at');
			//			$table->dateTime('last_modified_at');
			//			$table->dateTime('state_at');
		});
	}

	public function down():void
	{
		Schema::dropIfExists('listings');
	}
};
