<x-layouts.app>
	<x-slot:toolbar>
		<x-model.trigger.create
			for="create_product_type"
			model-name="Product Type"
		/>
	</x-slot:toolbar>
	<livewire:product-types.index-table/>
</x-layouts.app>
