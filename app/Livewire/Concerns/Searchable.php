<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;

trait Searchable
{
	#[Url]
	public string $search = '';
	/**
	 * @var list<string>
	 */
	protected array $searchColumns = [];

	/**
	 * @return list<string>
	 */
	protected function getSearchColumns():array
	{
		return $this->searchColumns;
	}

	public function bootSearchable():void
	{
		if ( !empty($columns = $this->getSearchColumns()) ) {
			$this->addQueryHandler(function ($query) use ($columns) {
				$query->when(
					$this->search,
					fn($query) => $query->where(function ($query) use ($columns) {
						foreach ( $columns as $column ) {
							$query
								->orWhere($column, 'like', "$this->search%")
								->orWhere($column, 'like', "%$this->search%");
						}
					})
				);
			});
		}
	}

	public function updatedSearch():void
	{
		$this->resetPage();
	}

}
