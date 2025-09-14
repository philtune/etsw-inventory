<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register():void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot():void
	{
		//	    URL::forceScheme(scheme: 'https');

		Collection::macro('paginate', function ($perPage, $pageName = 'page') {
			/** @var Collection $this */
			$currentPage = Paginator::resolveCurrentPage($pageName);
			return new LengthAwarePaginator(
				items: $this->slice(( $currentPage - 1 ) * $perPage, $perPage),
				total: $this->count(),
				perPage: $perPage,
				currentPage: $currentPage,
				options: ['pageName' => $pageName]
			);
		});

		ForeignIdColumnDefinition::macro('nullConstrained', function (
			?string $table = null,
			?string $column = null,
			?string $indexName = null
		) {
			/** @var ForeignIdColumnDefinition $self */
			$self = $this;
			return $self
				->nullable()
				->constrained($table, $column, $indexName)
				->cascadeOnUpdate()
				->nullOnDelete();
		});

		ForeignIdColumnDefinition::macro('cascadeConstrained', function (
			?string $table = null,
			?string $column = null,
			?string $indexName = null
		) {
			/** @var ForeignIdColumnDefinition $self */
			$self = $this;
			return $self
				->constrained($table, $column, $indexName)
				->cascadeOnUpdate()
				->cascadeOnDelete();
		});

		HasMany::macro('syncUsing', function (array $array, string $foreign_key = null) {
			/** @var HasMany $hasMany */
			$hasMany = $this;
			if ( is_null($foreign_key) ) {
				$foreign_key = $hasMany->getForeignKeyName();
			}
			// Delete items not given
			$hasMany
				->clone()
				->whereNotIn($foreign_key, array_keys($array))
				->each(fn(Model $model) => $model->delete());
			foreach ( $array as $foreign_id => $update_values ) {
				$hasMany->updateOrCreate([$foreign_key => $foreign_id], $update_values);
			}
		});
	}
}
