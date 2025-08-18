<div>
	@if( !$has_variants )
		<button
			type="button"
			class="u_btn --sm --bare"
			wire:click="add"
		>@svg('icon-plus') Add Variants
		</button>
	@else
		<div class="l_rows --sm">
			<label>
				<input
					type="text"
					class="input"
					placeholder="Label"
					name="variants[label]"
					wire:model.live.debounce="label"
					maxlength="24"
				/>
			</label>
			Options:
			<div>
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
										name="variants[options][{{ $i }}][key]"
										placeholder="key"
										wire:model.live.debounce="options.{{ $i }}.key"
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
										name="variants[options][{{ $i }}][value]"
										value="{{ $options[$i]['value'] }}"
										wire:model.live.debounce="options.{{ $i }}.value"
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
					</tbody>
				</table>
				@pushonce('below-body')
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
			<div>
				<button
					type="button"
					class="u_btn --sm --bare"
					wire:click="addOption"
				>@svg('icon-plus') Add Option
				</button>
			</div>
			@if( count($options) )
				<label>
					Default:
					<span style="display:inline-block"><x-form.select
							name="variants[default]"
							:options="$option_options"
							:default="$default"
							:initial="false"
						/></span>
				</label>
			@endif
		</div>
		<button
			type="button"
			class="u_btn --danger --sm --bare"
			wire:click="remove"
		>@svg('icon-xmark') Remove Variants
		</button>
	@endif
</div>
