<?php

namespace App\View\Components\Layouts;

use App\Models\OauthToken;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Nav extends Component
{
	public function render():View
	{
		return view('components.layouts.nav', [
			'items' => $this->getItems()
		]);
	}

	private function getItems():array
	{
		$etsyToken = OauthToken::getEtsyToken();
		return [
			[
				'href'   => route('home'),
				'label'  => 'Home',
				'active' => request()->routeIs('home'),
			],
			[
				'label' => 'Inventory',
				'items' => [

					[
						'href'   => route('product-types.index'),
						'label'  => 'Types',
						'active' => request()->routeIs('product-types.*'),
					],
					[
						'href'   => route('scents.index'),
						'label'  => 'Scents',
						'active' => request()->routeIs('scents.*'),
					],
					[
						'href'   => route('products.index'),
						'label'  => 'Products',
						'active' => request()->routeIs('products.index'),
					],
					[
						'href'   => route('products.stock'),
						'label'  => 'Manage Stock',
						'active' => request()->routeIs('products.stock'),
					],
				],
			],
			[
				'label' => 'Wholesale',
				'items' => [
					[
						'href'  => route('wholesale.dashboard'),
						'label' => 'Dashboard',
					],
					[
						'href'  => route('wholesale-customers.index'),
						'label' => 'Customers',
					],
					[
						'href'  => route('wholesale-orders.index'),
						'label' => 'Orders',
					]
				]
			],
			[
				'label' => '<img src="' . asset('img/etsy-favicon.ico') . '" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> Etsy',
				'items' => [
					[
						'href'   => route('etsy.listings.index'),
						'label'  => 'Listings',
						'active' => request()->routeIs('etsy.listings.index')
					],
//					[
//						'href'   => route('etsy.orders.index'),
//						'label'  => 'Orders',
//						'active' => request()->routeIs('etsy.orders.index')
//					],
					[
						'href'   => route('etsy.transactions.index'),
						'label'  => 'Transactions',
						'active' => request()->routeIs('etsy.transactions.index')
					]
				]
			]
		];
	}
}
