<x-layouts.app page-title="Edit Customer: {{ $wholesaleCustomer->name }}">
	<div class="l_cols --split">
		<p>
			<a href="{{ route('wholesale.dashboard') }}">Wholesale</a> &gt;
			<a href="{{ route('wholesale-customers.index') }}">Customers</a>
		</p>
		<div class="l_cols --sm">
			<a href="{{ route('wholesale-customers.orders.index', $wholesaleCustomer) }}">Orders</a> |
			<form action="{{ route('wholesale-customers.orders.store', $wholesaleCustomer) }}" method="POST">
				@csrf
				<button type="submit" class="u_btn --sm">New Order</button>
			</form>
		</div>
	</div>
	<form action="{{ route('wholesale-customers.update', $wholesaleCustomer) }}" method="POST">
		@csrf
		@method('PATCH')
		<table class="m_table">
			<tr>
				<th>
					<label for="name">Name</label>
				</th>
				<td>
					<input
						type="text"
						class="input w-100"
						name="name"
						id="name"
						maxlength="255"
						required
						value="{{ $wholesaleCustomer->name }}"
					/>
				</td>
			</tr>
			<tr>
				<th>
					<label for="primary_address">Address</label>
				</th>
				<td>
					<input
						type="text"
						class="input w-100"
						name="primary_address"
						id="primary_address"
						maxlength="255"
						value="{{ $wholesaleCustomer->primary_address }}"
					/>
				</td>
			</tr>
			<tr>
				<th>
					<label for="phone_numbers">Phone Number(s)</label>
				</th>
				<td>
					<input
						type="text"
						class="input w-100"
						name="phone_numbers"
						id="phone_numbers"
						maxlength="255"
						value="{{ $wholesaleCustomer->phone_numbers }}"
					/>
				</td>
			</tr>
			<tr>
				<th>
					<label for="notes">Notes</label>
				</th>
				<td>
					<textarea
						class="textarea w-100"
						name="notes"
						id="notes"
						maxlength="1024"
					>{{ $wholesaleCustomer->notes }}</textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button type="submit" class="u_btn">@svg('icon-check') Update</button>
				</td>
			</tr>
		</table>
	</form>
</x-layouts.app>
