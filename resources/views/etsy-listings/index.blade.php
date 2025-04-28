<x-layouts.app>
	<x-slot:toolbar>
		<a href="{{ route('etsy-listings.import') }}">Import</a>
	</x-slot:toolbar>
	<livewire:etsy-listings-table/>
</x-layouts.app>
