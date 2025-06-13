<nav class="l_cols --md">
	<a href="{{ route('home') }}">Home</a> |
	<a href="{{ route('scents.index') }}">Scents</a> |
	<a href="{{ route('product-types.index') }}">Product Types</a> |
	<a href="{{ route('products.index') }}">Products</a>
	<fieldset style="margin-bottom: 0.5rem;">
		<legend>Wholesale</legend>
		<a href="{{ route('wholesale.dashboard') }}">Dashboard</a> |
		<a href="{{ route('wholesale-customers.index') }}">Customers</a> |
		<a href="#">Orders</a>
	</fieldset>
	<fieldset style="margin-bottom: 0.5rem;">
		<legend><img src="https://www.etsy.com/images/favicon.ico" style="width:1rem;height:1rem;display:inline-block;vertical-align:middle" alt="Etsy"/> Etsy</legend>
		<a href="{{ route('etsy.listings.index') }}">Listings</a> |
		<a href="#">Orders</a> |
		<a href="#">Transactions</a> |
		{{ number_format(cache(\App\Services\EtsyApplicationApi::CALLS_REMAINING_TODAY)[0]) }} API calls remaining today
	</fieldset>
</nav>
