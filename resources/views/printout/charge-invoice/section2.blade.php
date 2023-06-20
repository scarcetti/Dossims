@php
$total =0;
$lm = 1;
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
                <td class="ob"colspan="3">{{ $transaction->customer->address }}</td>
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
                @if($products->linear_meters != null)
                    {{ $lm = $products->linear_meters }}
                @endif

                <tr>
                    {{ $products->linear_meters }}
                    <td align="center">{{ $products->quantity }}</td>
                    <td align="center">{{ $products->linear_meters }} {{ $products->branchProduct->product->measurementUnit->name }}</td>
                    <td align="center" colspan="2">{{ $products->branchProduct->product->name }}</td>
                    <td align="center">₱{{ $products->price_at_purchase }}</td>
                    <td align="center">₱{{ intval($products->quantity)*floatval($products->branchProduct->price)*intval($lm) }}</td>
                    @php
                        $total+=intval($products->quantity)*floatval($products->branchProduct->price)*intval($lm)
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
        {{-- {{ $transaction->transactionItems }} --}}
    </table>
</div>

<div style=" position: relative; font-size:18px; top: 110px;">
    <table width="100%" >
        <tbody>
            <tr>
                <td colspan="3">
                    <p align="justify" style="padding: 10px;">
                        <b>PROMISSORY NOTE: </b>
                        The undersigned buyer agree to pay the above account within 30 days after date. Failure to pay the above account after stated period of time the buyer shall be obliged to pay a surcharge of 20%. All civil action on this contract shall be instituted in the court of Davao City and the buyer agree to pay an attorney's fee and court cost should the seller instituted lagal action.
                    </p>
                </td>
            </tr>
            <tr style="height:100px;">
                <td class="nob" style="width: 33%;" valign="middle" align="center">
                    <h4 style="color:brown; font-size: 30px; ">{{ $transaction->txno }}</h4>
                </td>
                <td class="nob" style="width: 50%" valign="top" align="right" colspan="2">
                    <h6>Received the goods above in good order and condiitions. </h6>
                </td>
            </tr>
            <tr>
                <td class="nob" colspan="2"></td>
                <td class="ob" style="width: 33%">
                </td>
            </tr>
            <tr>
                <td class="nob" colspan="2"></td>
                <td class="nob" style="width: 33%" align="center">Customer's Signature</td>
            </tr>
            <tr>
                <td class="ob" style="width: 33%; bo" align="center">{{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}</td>
                <td class="nob"  colspan="2"></td>
            </tr>
            <tr>
                <td class="nob" style="width: 33%" align="center"> Cashier/ Authorized Representative</td>
                <td class="nob"  colspan="2"></td>
            </tr>
        </tbody>
    </table>
</div>

