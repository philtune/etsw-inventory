<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	    URL::forceScheme(scheme: 'https');

		Collection::macro('paginate', function ($perPage, $pageName = 'page') {
			/** @var Collection $this */
			$currentPage = Paginator::resolveCurrentPage($pageName);
			return new LengthAwarePaginator(
				items: $this->slice(($currentPage - 1) * $perPage, $perPage),
				total: $this->count(),
				perPage: $perPage,
				currentPage: $currentPage,
				options: ['pageName' => $pageName]
			);
		});
    }
}
