<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

trait Sortable
{

	#[Url]
	public string $order_column = '';
	#[Url]
	public bool $order_desc = false;

	/**
	 * @template TBuilder of \Illuminate\Contracts\Database\Query\Builder
	 * @return array<string,\Closure(TBuilder):void|TBuilder>
	 */
	protected function getCustomOrders():array
	{
		// example
		return [
			'newest' => fn(Builder $query) => $query->orderBy('created_at', 'desc')
		];
	}

	public function bootSortable():void
	{
		$this->addQueryHandler(fn($query) => $query->when(
			$this->order_column,
			function ($query) {
				$customOrders = $this->getCustomOrders();
				if ( array_key_exists($this->order_column, $customOrders) ) {
					$customOrders[$this->order_column]($query);
				} else {
					$query->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc');
				}
			}
		));
	}

	public function orderBy(string $field, bool $desc_first = false):void
	{
		if ( $this->order_column === $field ) {
			$this->order_desc = !$this->order_desc;
		} else {
			$this->order_desc = $desc_first;
		}
		$this->order_column = $field;
		$this->resetPage();
	}
}
