@props([
	/** @var ?\App\Models\WholesaleCustomer */
	'wholesaleCustomer' => null,
])
<x-layouts.app :$pageTitle>
	<x-slot:toolbar>
		<x-form.select-route
			:options="$wholesale_customer_options"
			:default="$wholesaleCustomer?->id"
			initial="-- ALL CUSTOMERS --"
			route-name="wholesale-customers.orders.index"
			:all="route('wholesale-orders.index')"
		/>
		@if( $wholesaleCustomer )
			<form action="{{ route('wholesale-customers.orders.store', $wholesaleCustomer) }}" method="POST">
				@csrf
				<button type="submit" class="u_btn">New Order</button>
			</form>
		@endif
	</x-slot:toolbar>
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
	<table class="m_table w-auto">
		<thead>
		<tr>
			<th>Count</th>
			<th>Items</th>
			<th>Revenue</th>
			<th>Customers</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>{{ $wholesaleOrders->count() }}</td>
			<td>{{ $wholesaleOrders->sum('wholesale_order_products_sum_quantity') }}</td>
			<td>${{ number_format($wholesaleOrders->sum('items_grand_total'), 2) }}</td>
			<td>{{ $wholesaleOrders->unique('wholesale_customer_id')->count() }}</td>
		</tr>
		</tbody>
	</table>

	<div class="m_table__container">
		<table class="m_table w-auto">
			<thead>
			<tr>
				<th class="w-1px">@svg('icon-ellipsis-vertical')</th>
				<th>Order Date</th>
				@if( !$wholesaleCustomer )
					<th>Customer</th>
				@endif
				<th>Products</th>
				<th>Subtotal</th>
				<th>Notes</th>
				<th>Invoice</th>
				<th class="w-1px">@svg('icon-ellipsis-vertical')</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $wholesaleOrders as $wholesaleOrder )
				<tr
					onclick="window.location='{{ route('wholesale-orders.show', $wholesaleOrder) }}'"
					style="cursor:pointer"
				>
					<td>
						<a
							class="u_btn --sm --tt-right"
							href="{{ route('wholesale-orders.show', $wholesaleOrder) }}"
							data-tooltip="View Order"
						>@svg('icon-eye')</a>
					</td>
					<td>{{ $wholesaleOrder->ordered_at->format('m/d/Y') }}</td>
					@if( !$wholesaleCustomer )
						<td>{{ $wholesaleOrder->wholesaleCustomer->name }}</td>
					@endif
					<td>{{ $wholesaleOrder->wholesale_order_products_count }} ({{ $wholesaleOrder->wholesale_order_products_sum_quantity }} items)</td>
					<td class="text-right">${{ number_format($wholesaleOrder->items_grand_total, 2) }}</td>
					<td>{{ Str::limit($wholesaleOrder->notes, 64) }}</td>
					<td onclick="event.stopPropagation()">
						@if( $wholesaleOrder->invoice_url )
							<a href="{{ $wholesaleOrder->invoice_url }}" target="_blank">
								@if( Str::startsWith($wholesaleOrder->invoice_url, ['https://www.paypal.com', 'https://paypal.com']) )
									@svg('icon-paypal')
									PayPal
								@elseif( Str::startsWith($wholesaleOrder->invoice_url, 'https://docs.google.com/spreadsheets') )
									@svg('icon-google-sheets') Sheets
								@elseif( Str::contains($wholesaleOrder->invoice_url, 'squarespace.com/config/invoicing/invoices'))
									@svg('icon-squarespace') Squarespace
								@else
									{{ Str::limit($wholesaleOrder->invoice_url, 32) }}
								@endif
							</a>
						@endif
					</td>
					<td onclick="event.stopPropagation()">
						<form method="POST" action="{{ route('wholesale-orders.delete', $wholesaleOrder) }}">
							@csrf
							@method('DELETE')
							<button
								type="submit"
								class="u_btn --danger --sm --tt-left"
								data-tooltip="Delete Order"
							>@svg('icon-trash-xmark')</button>
						</form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
