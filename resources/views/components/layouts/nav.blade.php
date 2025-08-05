<nav class="c_nav l_rows">
	<a href="{{ route('home') }}">Home</a>
	<fieldset class="l_rows">
		<legend>Products</legend>
		<a href="{{ route('product-types.index') }}">Product Types</a>
		<a href="{{ route('scents.index') }}">Scents</a>
		<a href="{{ route('products.index') }}">Edit Products</a>
		<a href="{{ route('products.stock') }}">Manage Stock</a>
	</fieldset>
	<fieldset class="l_rows">
		<legend>Wholesale</legend>
		<a href="{{ route('wholesale.dashboard') }}">Dashboard</a>
		<a href="{{ route('wholesale-customers.index') }}">Customers</a>
		<a href="{{ route('wholesale-orders.index') }}">Orders</a>
	</fieldset>
	<fieldset class="l_rows">
		<legend><img src="https://www.etsy.com/images/favicon.ico" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> Etsy</legend>
		<a href="{{ route('etsy.listings.index') }}">Listings</a>
		<a href="#">Orders</a>
		<a href="{{ route('etsy.transactions.index') }}">Transactions</a>
		{{ number_format(\App\Models\OauthToken::getEtsyToken()?->remaining_today) }} API calls remaining today
	</fieldset>
</nav>
