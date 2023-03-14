@php
  function subtotal($item) {
    if($item->linear_meters) {
      return $item->linear_meters * $item->price_at_purchase;
    }
    else {
      return $item->quantity * $item->price_at_purchase; 
    }
  }
@endphp

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/printouts.css') }}"> --}}
<style type="text/css">
  /*.1_ {
  border-top: solid 1px;
}

.2_ {
  border-right: solid 1px;
}

.3_ {
  border-bottom: solid 1px;
}

.4_ {
  border-left: solid 1px;
}*/
td {
  border: solid 1px;
}
</style>
<table>
  <thead>
    <tr>
      <th>Product</th>
      <th>Quantity</th>
      <th>Unit price</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($transaction->transactionItems as $item)
      <tr>
        <td class="1_ 2_">{{ $item->branchProduct->product->name }}</td>
        <td class="1_ 2_">{{ $item->quantity }}</td>
        <td class="1_ 2_">{{ $item->price_at_purchase }}</td>
        <td class="1_ 2_">{{ subtotal($item) }}</td>
      </tr>
    @endforeach
  </tbody>
  Customer name: {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
</table>