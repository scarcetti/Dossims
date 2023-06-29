@php
$total =0;
$lm = 1;
$deliveryFee = $transaction->payment->delivery_fees !== null  ? $transaction->payment->delivery_fees->total : "0";
@endphp
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
                <td class="ob"colspan="4">{{ $transaction->customer->address }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div style=" position: relative; font-size:18px; top: 20px;">
    <table width="100%" >
        <tbody>
            @foreach( $transaction->transactionItems as $products )
                @if ($products->branchProduct->product->ready_made == false)
                    @if($products->linear_meters != null)
                        @php
                            $lm = $products->linear_meters
                        @endphp
                    @endif

                    <tr align="center">
                        <td style="font-weight: bold;" colspan="2"> {{ $products->branchProduct->product->productCategory->name }} </td>
                        <td colspan="2">{{ $products->branchProduct->product->name }}</td>
                        <td style="font-weight: bold;">Color:</td>
                        <td>
                            @if($products->job_order_note != "[]")
                                {{ $products->job_order_note }}
                            @endif
                        </td>
                    </tr>
                    <tr style="font-weight: bold;" align="center">
                        <td>QTY</td>
                        <td>UNIT</td>
                        <td colspan="2">MEASUREMENT</td>
                        <td>UNIT<br>PRICE</td>
                        <td>TOTAL<br>PRICE</td>
                    </tr>
                    <tr>
                        <td align="center">{{ $products->quantity }}  </td>
                        <td align="center">{{ $products->branchProduct->product->measurementUnit->name }}</td>
                        <td align="center" colspan="2">{{ $lm }} {{ $products->branchProduct->product->measurementUnit->name }}</td>
                        <td align="center">{{ '₱' . number_format($products->branchProduct->price, 2) }}</td>
                        <td align="center">{{ '₱' . number_format(intval($products->quantity)*floatval($products->branchProduct->price)*intval($lm), 2)  }}</td>
                    </tr>
                    @php
                        $total+=intval($products->quantity)*floatval($products->branchProduct->price)*intval($lm)
                    @endphp
                @endif
            @endforeach

            </tbody>
            {{-- {{ $transaction }} --}}
    </table>
    <table width="100%">
        <tbody>
            <tr>
                <td class="nob">Delivery Fee:</td>
                <td  align="center" class="ob">{{ '₱' . number_format($deliveryFee, 2) }}</td>
            </tr>
            <tr>
                <td class="nob">Laboratory Installation:</td>
                <td  align="center" class="ob"></td>
            </tr>
            <tr>
                <td class="nob">Grand Total:</td>
                <td  align="center" class="ob">{{ '₱' . number_format($total, 2) }}</td>
            </tr>
            <tr>
                <td class="nob">Prepared by:</td>
                <td  align="center" class="ob">{{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}</td>
            </tr>
        </tbody>
    </table>
</div>
{{-- {{ $transaction }} --}}
