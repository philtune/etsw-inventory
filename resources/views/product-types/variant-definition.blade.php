<div>
	@if( !$has_variants )
		<button
			type="button"
			class="u_btn --sm --bare"
			wire:click="add"
		>@svg('icon-plus') Add Variants
		</button>
	@else
		<table class="m_table">
			<tbody>
			<tr>
				<th>Label</th>
				<td>
					<input
						type="text"
						class="input"
						name="variant_label"
						wire:model.live.debounce="label"
						maxlength="24"
					/>
				</td>
			</tr>
			<tr>
				<th>Options</th>
				<td>
					<table class="m_table --sheet w-auto">
						<thead>
						<tr>
							<th>Key</th>
							<th>Aliases</th>
							<x-th.actions/>
						</tr>
						</thead>
						<tbody>
						@foreach( $options as $i => $option )
							<tr>
								<td>
									<label>
										<input
											type="text"
											class="input"
											name="variants[{{ $i }}][label]"
											value="{{ $option['label'] }}"
											wire:model.live.debounce="options.{{ $i }}.label"
											maxlength="16"
											style="width:4rem;"
										/>
									</label>
								</td>
								<td>
									<label>
										<input
											type="text"
											class="z_variant_aliases"
											name="variants[{{ $i }}][aliases]"
											value="{{ $option['aliases'] }}"
											wire:model.live.debounce="options.{{ $i }}.aliases"
											maxlength="24"
											data-taggable
										/>
									</label>
								</td>
								<td>
									<button
										type="button"
										class="u_btn --danger --sm --bare"
										wire:click="removeOption('{{ $i }}')"
									>@svg('icon-xmark')</button>
								</td>
							</tr>
						@endforeach
						<tr>
							<td colspan="3">
								<button
									type="button"
									class="u_btn --sm --bare"
									wire:click="addOption"
								>@svg('icon-plus') Add Option
								</button>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			@if( !empty($option_options) )
				<tr>
					<th>Default</th>
					<td>
						<x-form.select
							name="variant_default"
							:options="$option_options"
							:default="$default"
							:initial="false"
						/>
					</td>
				</tr>
			@endif
			</tbody>
		</table>
		<button
			type="button"
			class="u_btn --danger --sm --bare"
			wire:click="remove"
		>@svg('icon-xmark') Remove Variants
		</button>
	@endif@pushonce('below-body')
		<style>
			.z_variant_aliases {
				max-width: 16rem;
			}
		</style>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				const init = (_parent) => {
					_parent = _parent || document
					_parent.querySelectorAll('[data-taggable]').forEach(_me => {
						if ( _me.tomselect ) {
							_me.tomselect.destroy()
						}
						new TomSelect(_me, {
							persist: true,
							createOnBlur: true,
							create: true,
							refreshThrottle: 0
						})
					})
				}
				if ( typeof Livewire !== 'undefined' ) {
					Livewire.hook('morphed', ({el}) => {
						init(el)
					})
				}
				init()
			})
		</script>
	@endpushonce
</div>
