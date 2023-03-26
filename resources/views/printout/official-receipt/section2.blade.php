@php
    $or_fields = ['Received from', 'with TIN', 'and address at', 'business style of', 'the sum of', 'PESOS', 'In payment for'];
@endphp
<div class="row" style="">
	<span>
		<img alt="asd" src="{{public_path('images/domings.png')}}" style="width: 200px; margin-top: -20px">
	</span>

	<div style="position: absolute; right: 0; top: 0;">
		<div style="font-size: 27px; font-weight: bold;">
			DOMING'S STEEL TRADING
		</div>
		<div>
			Prk. 5, McArthur Highway. Brgy Lizada, Toril, Davao City
		</div>
		<div>
			Franz Marlon C. Quilban - Prop *VAT Reg. TIN: 416-676-701-002
		</div>
		<div>
			Cell no. Smart 09209680226 / Globe 09752874632
		</div>
	</div>
</div>
<br><br>
<div class="row" style=" position: relative;" >
	<div style="font-size: 25px; font-weight: bold;">
		OFFICIAL RECEIPT
	</div>
	<div style="position: absolute; right: 0; top: 5px;">
		Date ____________
	</div>
</div>
<div style=" position: relative; font-size:18px">
	<table width="100%">
		<tbody>
			@foreach($or_fields as $item)
				<tr>
					<td class="nob" style="width: 15%">{{ $item }}</td>
					@if($item != 'the sum of')
						<td class="ob">
							@if($item == 'PESOS')
								₱
							@endif
						</td>
					@else
						<td class="nob"></td>
					@endif
				</tr>
				@if($item == 'the sum of')
					<tr>
						<td class="nob"></td>
						<td class="ob"></td>
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</div>
<div style=" position: relative; margin-top: 20px;">
	<div style="float: left; width: 50%;">
		<table width="90%">
			<tbody>
				<tr>
					<td colSpan="2">Sr. Citizen TIN</td>
				</tr>
				<tr>
					<td class="nb">OSCA/PWS ID No.</td>
					<td class="nb">Signature</td>
				</tr>
				<tr>
					<td class="nt"></td>
					<td class="nt"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="float: right; width: 50%; margin-top: 45px">
		By: _______________________________________<br>
		@foreach(range(1, 7) as $i) &nbsp; @endforeach
		Cashier / Authorized Representative
	</div>
	</div>
</div>
