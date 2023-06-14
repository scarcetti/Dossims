@php
$total =0;
$balance = $transaction->customer->balance !== null ? $transaction->customer->balance->outstanding_balance : "0";
// $showOfficialReceipt = $transaction->payment->payment_method_id == 1 ? true:false;
@endphp
<div style=" position: relative; font-size:18px">
    <table width="100%">
        <tbody>
            <tr>
                <td class="nob" style="width: 20%">Name:</td>
                <td class="ob" style="width: 25%" colspan="2">{{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</td>
                <td class="nob" style="width: 15%">Date</td>
                <td class="ob" style="width: 25%">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="nob">Address:</td>
                <td class="ob"colspan="4"></td>
            </tr>
        </tbody>
    </table>
</div>
<div style=" position: relative; font-size:18px; top: 20px;">
    <table width="100%" >
        <tbody>
            <tr style="font-weight: bold;" align="center">
                <td>QTY</td>
                <td>UNIT</td>
                <td colspan="2">DESCRIPTION</td>
                <td>U.P.</td>
                <td>Amount</td>
            </tr>
            @foreach( $transaction->transactionItems as $products )

                @if ($products->branchProduct->product->ready_made == false)
                    <tr>
                        <td align="center">{{ $products->quantity }}</td>
                        <td align="center">{{ $products->linear_meters }} {{ $products->branchProduct->product->measurementUnit->name }}</td>
                        @if ($products->branchProduct->product->ready_made == false)
                            <td align="center" colspan="2">{{ $products->branchProduct->product->name }}</td>
                        @endif
                        <td align="center">₱{{ $products->price_at_purchase }}</td>
                        <td align="center">₱{{ intval($products->quantity)*floatval($products->branchProduct->price)*intval($products->linear_meters)  }}</td>
                        @php
                            $total+=intval($products->quantity)*floatval($products->branchProduct->price)*intval($products->linear_meters)
                        @endphp
                    </tr>
                @endif
            @endforeach


            <tr>
                <td class="basta" colspan="5">Total </td>
                <td class="basta" style="text-align: center">P{{$total}}</td>
            </tr>

            <tr>
                <td class="basta" colspan="5">AMOUNT ₱</td>
        <td class="basta" style="text-align: center">{{ $transaction->payment->amount_paid }}</td>
            </tr>
            <tr>
                <td class="basta" colspan="5">ADVANCE ₱</td>
                <td class="basta" style="text-align: center">{{ $transaction->payment->amount_paid-$balance }}</td>
            </tr>
            <tr>
                <td class="basta" colspan="5">BALANCE ₱</td>
                <td class="basta" style="text-align: center">{{ $balance }}</td>
            </tr>

        </tbody>
    </table>
    {{-- {{$transaction}} --}}
</div>
<div style=" position: relative; font-size:18px;">
    <table width="100%" >
        <tbody>
            <tr style="height:100px;">
                <td class="nob" style="width: 33%;" valign="middle" align="center">
                    <h4 style="color:brown; font-size: 30px; ">{{ $transaction->txno }}</h4>
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
