<x-layouts.app page-title="Wholesale Customers">
	<form action="{{ route('wholesale-customers.store') }}" method="POST">
		@csrf
		<strong>Add Customer:</strong>
		<label>
			Name
			<input
				type="text"
				class="input"
				name="name"
				maxlength="255"
				required
			/>
		</label>
		<button type="submit" class="u_btn">@svg('icon-plus') Add</button>
	</form>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Phone Number(s)</th>
				<th>Notes</th>
				<th>Orders</th>
				<th class="w-1px"><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $wholesaleCustomers as $wholesaleCustomer )
				<tr>
					<td>{{ $wholesaleCustomer->name }}</td>
					<td>{{ $wholesaleCustomer->primary_address }}</td>
					<td>{{ $wholesaleCustomer->phone_numbers }}</td>
					<td>{{ Str::limit($wholesaleCustomer->notes, 64) }}</td>
					<td>{{ $wholesaleCustomer->wholesale_orders_count }}</td>
					<td style="display:flex;align-items:center;gap:0.25rem">
						<a href="{{ route('wholesale-customers.show', $wholesaleCustomer) }}">View</a> |
						<a href="{{ route('wholesale-customers.orders.index', $wholesaleCustomer) }}">Orders</a> |
						<form action="{{ route('wholesale-customers.orders.store', $wholesaleCustomer) }}" method="POST">
							@csrf
							<button type="submit" class="u_btn --sm">New Order</button>
						</form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
