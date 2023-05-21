@php
$total =0
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
                <td class="ob"colspan="4">Unahan sa Tumoy</td>
            </tr>
        </tbody>
    </table>
</div>
<div style=" position: relative; font-size:18px; top: 20px;">
    <table width="100%" >
        <tbody>
            <tr style="font-weight: bold;" align="center">
                <td>QTY</td>
                <td colspan="2">DESCRIPTION</td>
                <td>U.P.</td>
                <td>Amount</td>
            </tr>
            @foreach( $transaction->transactionItems as $products )
                @if ($products->branchProduct->product->ready_made = "true")
                    <tr>
                        <td align="center">{{ $products->quantity }}</td>
                        @if ($products->branchProduct->product->ready_made = "true")
                            <td align="center" colspan="2">{{ $products->branchProduct->product->name }}</td>
                        @endif

                        <td align="center"></td>
                        <td align="center">P{{ intval($products->quantity)*floatval($products->branchProduct->price) }}</td>
                        @php
                            $total+=intval($products->quantity)*floatval($products->branchProduct->price)
                        @endphp
                    </tr>
                @endif


            @endforeach
            <tr>
                <td class="basta" colspan="4">Total </td>
                <td class="basta" style="text-align: center">P{{$total}}</td>
            </tr>
        {{--     <tr>
                <td class="basta" colspan="4">AMOUNT P</td>
                <td class="basta" style="text-align: center">218</td>
            </tr>
            <tr>
                <td class="basta" colspan="4">ADVANCE P</td>
                <td class="basta" style="text-align: center">218</td>
            </tr>
            <tr>
                <td class="basta" colspan="4">BALANCE P</td>
                <td class="basta" style="text-align: center">218</td>
            </tr> --}}

        </tbody>
    </table>
    {{-- {{$transaction}} --}}
</div>
