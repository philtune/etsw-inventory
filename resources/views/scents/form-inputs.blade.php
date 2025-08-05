@props([
	/** @var \App\Models\Scent */
	'scent' => null,
	'uid' => 'id_' . uniqid(),
])
<table class="m_table">
	<tr>
		<th><label for="{{ $uid }}_code">Code</label></th>
		<td>
			<input
				class="input --sm"
				name="code"
				value="{{ $scent?->code }}"
				maxlength="16"
				id="{{ $uid }}_code"
			/>
		</td>
	</tr>
	<tr>
		<th><label for="{{ $uid }}_label">Label</label></th>
		<td>
			<input
				class="input"
				name="label"
				value="{{ $scent?->label }}"
				maxlength="255"
				id="{{ $uid }}_label"
			/>
		</td>
	</tr>
</table>
