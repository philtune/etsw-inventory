<x-layouts.app page-title="Product Inventory">
	<x-slot:toolbar>
		<a class="u_btn --accent" href="{{ route('etsy-api.import-inventory') }}">@svg('icon-rotate') Update Etsy Inventory</a>
	</x-slot:toolbar>
	<livewire:products.stock-table/>
</x-layouts.app>
