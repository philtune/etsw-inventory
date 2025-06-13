@props([
	/** @var ?\App\Models\WholesaleCustomer */
	'wholesaleCustomer' => null,
])
<x-layouts.app :$pageTitle>
	<p>
		<a href="{{ route('wholesale.dashboard') }}">Wholesale</a> &gt;
		@if( $wholesaleCustomer )
			<a href="{{ route('wholesale-customers.index') }}">Customers</a> &gt;
			<a href="{{ route('wholesale-customers.show', $wholesaleCustomer) }}">{{ $wholesaleCustomer->name }}</a> &gt;
			Orders |
			<a href="{{ route('wholesale-orders.index') }}">All Orders</a>
		@else
			Orders
		@endif
	</p>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Order Date</th>
				@if( !$wholesaleCustomer )
					<th>Customer</th>
				@endif
				<th>Products</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Status</th>
				<th>Delivery Method</th>
				<th>Notes</th>
				<th class="text-center w-1px"><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $wholesaleOrders as $wholesaleOrder )
				<td>{{ $wholesaleOrder->ordered_at->format('m/d/Y - g:ia') }}</td>
				@if( !$wholesaleCustomer )
					<td>{{ $wholesaleOrder->wholesaleCustomer->name }}</td>
				@endif
				<td>{{ $wholesaleOrder->wholesale_order_line_items_count }}</td>
				<td>${{ ( $wholesaleOrder->items_grand_total ?: $wholesaleOrder->subtotal ) + $wholesaleOrder->total_adjustment }}</td>
				<td>${{ $wholesaleOrder->amount_paid }}</td>
				<td>{{ $wholesaleOrder->status() }}</td>
				<td>{{ $wholesaleOrder->delivery_method() }}</td>
				<td>{{ Str::limit($wholesaleOrder->notes, 64) }}</td>
				<td>
					<a href="{{ route('wholesale-orders.show', $wholesaleOrder) }}">View</a>
				</td>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
