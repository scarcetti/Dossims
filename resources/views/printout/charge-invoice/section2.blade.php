@php
$total =0
@endphp
<div style=" position: relative; font-size:18px">
    <table width="100%">
        <tbody>
            <tr>
                <td class="nob">Charge to:</td>
                <td class="ob" colspan="3">
                    {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                </td>
            </tr>
            <tr>
                <td class="nob">Address:</td>
                <td class="ob"colspan="3"></td>
            </tr>
            <tr>
                <td class="nob" style="width: 20%">TIN/SC-TIN:</td>
                <td class="ob"  style="width: 25%">&nbsp;</td>
                <td class="nob" style="width: 20%">Terms:</td>
                <td class="ob" style="width: 30%">&nbsp;</td>
            </tr>
            <tr>
                <td class="nob">Business Style:</td>
                <td class="ob">&nbsp;</td>
                <td class="nob">OSCA/PWD ID No.:</td>
                <td class="ob">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
{{-- TEST : {{$transaction}} --}}

<div style=" position: relative; font-size:18px; top: 20px; text-align:'center'">
    <table width="100%" >
        <tbody>
            <tr style="font-weight: bold;" align="center">
                <td style="width:10%">Qty</td>
                <td style="width:10%">Unit</td>
                <td style="width:50%" colspan="2">ARTICLES</td>
                <td style="width:10%">U.P.</td>
                <td style="width:20%">Amount</td>
            </tr>
            @foreach( $transaction->transactionItems as $products )
                <tr>
                    <td align="center">{{ $products->quantity }}</td>
                    <td align="center">{{ $products->tbd }}</td>
                    <td align="center" colspan="2">{{ $products->branchProduct->product->name }}</td>
                    <td align="center"></td>
                    <td align="center">P{{ intval($products->quantity)*floatval($products->branchProduct->price) }}</td>
                    @php
                        $total+=intval($products->quantity)*floatval($products->branchProduct->price)
                    @endphp
                </tr>

            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-right: 10px" align="right">Total Sales (VAT Incl.)</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-right: 10px"  align="right">Less: VAT</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="3" style="padding-right: 10px" align="right">Amount et of VAT</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-right: 10px" align="right">VATable Sales</td>
                <td colspan="2" style="padding-right: 10px" align="right">Less SC/PWD Disc.</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-right: 10px" align="right">VAT-Exempt Sales</td>
                <td colspan="2" style="padding-right: 10px" align="right">Amount Due</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-right: 10px" align="right">Zero Rated Sales</td>
                <td colspan="2" style="padding-right: 10px" align="right">Add:VAT</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3" style="padding-right: 10px" align="right">VAT Amount</td>
                <td colspan="2" style="font-weight: bold;padding-right: 10px" align="right">TOTAL AMOUNT DUE</td>
                <td align="center" style="font-weight: bold">P{{$total}}</td>
            </tr>
        </tbody>
    </table>
</div>
