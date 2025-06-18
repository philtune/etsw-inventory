<div>
	<p>
		<a href="{{ route('wholesale.dashboard') }}">Wholesale</a> &gt;
		@if( $wholesaleCustomer )
			<a href="{{ route('wholesale-customers.index') }}">Customers</a> &gt;
			<a href="{{ route('wholesale-customers.show', $wholesaleCustomer) }}">{{ $wholesaleCustomer->name }}</a> &gt;
			<a href="{{ route('wholesale-customers.orders.index', $wholesaleCustomer) }}">Orders</a> &gt;
			{{ $wholesaleOrder->ordered_at->format('m/d/Y - g:ia') }} |
			<a href="{{ route('wholesale-orders.index') }}">All Orders</a>
		@else
			<a href="{{ route('wholesale-orders.index') }}">Orders</a> &gt;
			{{ $wholesaleOrder->ordered_at->format('m/d/Y - g:ia') }}
		@endif
	</p>
		<table class="m_table w-auto">
			<tr>
				<th>Customer</th>
				<td>
					<x-form.select
						wire:model.live="wholesale_customer_id"
						:options="$wholesale_customer_options"
						:default="$wholesaleCustomer?->id"
						initial="-- CUSTOMER --"
					/>
				</td>
			</tr>
			<tr>
				<th>Ordered on</th>
				<td>
					<input
						type="date"
						class="input"
						wire:model.blur="ordered_at"
						required
					/>
				</td>
			</tr>
			<tr>
				<th>Notes</th>
				<td>
					<textarea
						class="textarea"
						wire:model.blur="notes"
					>{{ $wholesaleOrder->notes }}</textarea>
				</td>
			</tr>
		</table>
	<br/>
	<div class="m_table__container">
		<table class="m_table w-auto">
			<thead>
			<tr>
				<th>Product</th>
				<th>Variation</th>
				<th>Quantity</th>
				<th>Price Per Unit</th>
				<th>Adjustments</th>
				<th>Item Total</th>
				<th>Notes</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $wholesaleOrderProducts as $wholesaleOrderProduct )
				<livewire:wholesale-order-product-form
					:$wholesaleOrderProduct
					:$product_options
					:key="$wholesaleOrderProduct->id"
				/>
			@endforeach
			<tr>
				<td>&nbsp;</td>
				<td><strong>Subtotals:</strong></td>
				<td><strong>{{ $items_quantity }}</strong></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="text-right"><strong>${{ $wholesaleOrder->grand_total }}</strong></td>
				<td>&nbsp;</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
