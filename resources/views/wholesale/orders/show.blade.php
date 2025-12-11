<x-layouts.app page-title="Wholesale Order for {!! $wholesaleOrder->wholesaleCustomer->name !!} - {{ $wholesaleOrder->ordered_at->format('m/d/Y') }}">
	<livewire:wholesale-order-form :$wholesaleOrder/>
</x-layouts.app>
