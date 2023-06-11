<div style=" position: relative; font-size:18px">
    <table width="100%">
        <tbody>
            <tr>
                <td class="nob" style="width: 20%">Customer/ Company:</td>
                <td class="ob" style="width: 25%" colspan="2">{{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</td>
                <td class="nob" style="width: 15%">Date:</td>
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
        {{-- {{ $transaction }} --}}
        <tbody>
            {{-- <tr style="font-weight: bold;" align="center">
                <td>PROFILE:</td>
                <td>COLOR:</td>
            </tr>
            @foreach( $transaction->transactionItems as $products )

                @if ($products->branchProduct->product->ready_made == false)
                    <tr>
                        <td align="center">{{ $products->quantity }}</td>
                        @if ($products->branchProduct->product->ready_made == false)
                            <td align="center" colspan="2">{{ $products->branchProduct->product->name }}</td>
                        @endif
                    </tr>
                @endif
            @endforeach --}}

            <tr style="font-weight: bold;" align="center">
                <td colspan="2">Panel Type:</td>
                <td colspan="2"></td>
                <td>Color:</td>
                <td></td>
            </tr>
            <tr style="font-weight: bold;" align="center">
                <td>QTY</td>
                <td>UNIT</td>
                <td colspan="2">MEASUREMENT</td>
                <td>UNIT<br>PRICE</td>
                <td>TOTAL<br>PRICE</td>
            </tr>
            <tr align="center">
                @foreach( $transaction->transactionItems as $products )
                    @if ($products->branchProduct->product->ready_made == false)
                        <td>QTY</td>
                        <td>UNIT</td>
                        <td colspan="2">MEASUREMENT</td>
                        <td>UNIT<br>PRICE</td>
                        <td>TOTAL<br>PRICE</td>
                    @endif
                @endforeach
            </tr>
            <tr style="font-weight: bold;" align="center">
                <td colspan="2">BENDED:</td>
                <td colspan="2"></td>
                <td>Color:</td>
                <td></td>
            </tr>


        </tbody>
    </table>
</div>
