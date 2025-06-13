<x-layouts.app page-title="Wholesale Order for {{ $wholesaleOrder->wholesaleCustomer->name }}">
	<p>
		<a href="{{ route('wholesale.dashboard') }}">Wholesale</a> &gt;
		<a href="{{ route('wholesale-customers.index') }}">Customers</a> &gt;
		<a href="{{ route('wholesale-customers.show', $wholesaleOrder->wholesaleCustomer) }}">{{ $wholesaleOrder->wholesaleCustomer->name }}</a> &gt;
		<a href="{{ route('wholesale-customers.orders.index', $wholesaleOrder->wholesaleCustomer) }}">Orders</a> &gt;
		{{ $wholesaleOrder->ordered_at->format('m/d/Y - g:ia') }} |
		<a href="{{ route('wholesale-orders.index') }}">All Orders</a>
	</p>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Product</th>
				<th>Variation</th>
				<th>Quantity</th>
				<th>Price Per Unit</th>
				<th>Adjustments</th>
				<th>Grand Total</th>
				<th>Notes</th>
				<th>Status</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $wholesaleOrderLineItems as $wholesaleOrderLineItem )
				<tr>
					<td>{{ $wholesaleOrderLineItem->product->label }}</td>
					<td>{{ $wholesaleOrderLineItem->variation }}</td>
					<td>{{ $wholesaleOrderLineItem->quantity }}</td>
					<td>${{ $wholesaleOrderLineItem->price_per_unit }}</td>
					<td>${{ $wholesaleOrderLineItem->total_adjustment }}</td>
					<td>${{ $wholesaleOrderLineItem->quantity * $wholesaleOrderLineItem->price_per_unit + $wholesaleOrderLineItem->total_adjustment }}</td>
					<td>{{ $wholesaleOrderLineItem->notes }}</td>
					<td>{{ $wholesaleOrderLineItem->status() }}</td>
				</tr>
			@endforeach
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><strong>{{ $items_quantity }}</strong></td>
				<td>&nbsp;</td>
				<td><strong>${{ $wholesaleOrder->total_adjustment }}</strong></td>
				<td><strong>${{ $wholesaleOrder->grand_total }}</strong></td>
				<td>{{ $wholesaleOrder->notes }}</td>
				<td>{{ $wholesaleOrder->status() }}</td>
			</tr>
			</tbody>
		</table>
	</div>
</x-layouts.app>
