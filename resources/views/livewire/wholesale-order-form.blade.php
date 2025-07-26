<div>
	<p>
		<a href="{{ route('wholesale.dashboard') }}">Wholesale</a> &gt;
		@if( $wholesaleCustomer )
			<a href="{{ route('wholesale-customers.index') }}">Customers</a> &gt;
			<a href="{{ route('wholesale-customers.show', $wholesaleCustomer) }}">{{ $wholesaleCustomer->name }}</a> &gt;
			<a href="{{ route('wholesale-customers.orders.index', $wholesaleCustomer) }}">Orders</a> &gt;
			{{ $wholesaleOrder->ordered_at->format('m/d/Y') }} |
			<a href="{{ route('wholesale-orders.index') }}">All Orders</a>
		@else
			<a href="{{ route('wholesale-orders.index') }}">Orders</a> &gt;
			{{ $wholesaleOrder->ordered_at->format('m/d/Y') }}
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
					style="width:20rem"
				></textarea>
			</td>
		</tr>
		<tr>
			<th>Invoice URL</th>
			<td>
				<input
					type="url"
					class="input"
					wire:model.blur="invoice_url"
				/>
			</td>
		</tr>
	</table>
	<br/>
	<table class="m_table w-auto">
		<thead>
		<tr>
			<th><span data-tooltip="Actions" class="--tt-right">@svg('icon-ellipsis-vertical')</span></th>
			<th>Product</th>
			<th>Variation</th>
			<th>Quantity</th>
			<th>Price Per Unit</th>
			<th>Adj</th>
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
				:is_last="$loop->last"
			/>
		@endforeach
		<tr>
			<td class="text-center">
				<a href="#" wire:click.prevent="addProduct" class="u_btn --sm">@svg('icon-plus') Add row</a>
			</td>
			<td><strong>Total Products:</strong> {{ $wholesaleOrderProducts->count() }}</td>
			<td class="text-right" colspan="2"><strong>Total Items:</strong> {{ $items_quantity }}</td>
			<td></td>
			<td class="text-right" colspan="2"><strong>Subtotal:</strong> ${{ $wholesaleOrder->grand_total }}</td>
			<td></td>
		</tr>
		</tbody>
	</table>
</div>
