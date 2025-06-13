<x-layouts.app
	page-title="Etsy Listings"
>
	<x-slot:toolbar>
		<a class="u_btn --accent" href="{{ route('etsy-api.import-listings') }}">@svg('icon-file-import') Import</a>
	</x-slot:toolbar>
	<livewire:etsy-listings.index-table/>
</x-layouts.app>
