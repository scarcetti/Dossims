@php
    $invoice_fields = ['Total Sales', 'Less VAT', 'Total', 'Less: SC/PWD Disc', 'Total Due', 'Less: Withhodling Tax', 'Amount Due', '', 'VATable Sales', 'VAT-Exempt Sales', 'Zero Rated Sales', 'Vat Amount', 'Total Sales'];
@endphp
<table width="100%">
	<thead>
		<tr>
			<th colspan="2">
				In settlement of the following
			</th>
		</tr>
		<tr>
			<th>
				Billing invoice No.
			</th>
			<th>
				Amount
			</th>
		</tr>
	</thead>
	<tbody>
		<tbody>
			@foreach($invoice_fields as $item)
				<tr>
					<td>{{ $item }}</td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
		<thead>
			<tr>
				<th colspan="2">FORM OF PAYMENT</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<label>Cash</label>
					<input type="checkbox" name="">
				</td>
				<td>
					<label>Check</label>
					<input type="checkbox" name="">
				</td>
			</tr>
		</tbody>
	</tbody>
</table>