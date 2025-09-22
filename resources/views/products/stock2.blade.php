<x-layouts.app page-title="Product Stock 2">
	<x-slot:toolbar>
		<a class="u_btn --accent" href="{{ route('etsy-api.import-inventory') }}">@svg('icon-rotate') Update Etsy Stock</a>
	</x-slot:toolbar>
	<livewire:products.stock-table2/>
</x-layouts.app>
