<x-layouts.app>
	<p>
		<a href="{{ route('wholesale-customers.index') }}">Customers</a> ({{ $customers_count }})
	</p>
	<p>
		<a href="{{ route('wholesale-orders.index') }}">Orders</a> ({{ $orders_count }})
	</p>
</x-layouts.app>
