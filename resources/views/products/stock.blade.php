<x-layouts.app page-title="Manage Stock">
	<x-slot:toolbar>
		<a class="u_btn --accent" href="{{ route('etsy-api.import-inventory') }}">@svg('icon-rotate') Update Etsy Stock</a>
	</x-slot:toolbar>
	<livewire:products.manage-stock/>
</x-layouts.app>
