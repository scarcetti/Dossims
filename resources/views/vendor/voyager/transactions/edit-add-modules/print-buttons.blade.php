@php

$showChargeInvoice = false;
$showCashInvoice = true;
$showCuttingList = false;
$showDeliveryReceipt = false;
$showJobOrder = false;
$showOfficialReceipt = false;
$showDeliveryReceipt = $transaction->payment->delivery_fees != null ? $transaction->payment->delivery_fees->total != 0 :true;
$showChargeInvoice = $transaction->customer->balance != null ? ($transaction->customer->balance->outstanding_balance != 0 ) : true;
$showOfficialReceipt = $transaction->customer->balance != null ? $transaction->customer->balance->outstanding_balance == 0 : true;

foreach( $transaction->transactionItems as $products ){
    if($products->branchProduct->product->ready_made == false){
        $showCashInvoice = false;
        $showCuttingList = true;
        $showJobOrder= true;
        break;
    }
}

@endphp
<div class="payment_container">
    <div class="total_container">
        <h4>Download printables</h4>
        {{-- <h2 style="font-weight: bold;">smeagol</h2> --}}
        <div style="width: 100%">
            @if ($showChargeInvoice == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/charge-invoice/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Charge Invoice</span>
                </a>
            @endif
            @if ($showCashInvoice == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/cash-invoice/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Cash Invoice</span>
                </a>
            @endif
            @if ($showCuttingList == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/cutting-list/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Cutting List</span>
                </a>
            @endif
            @if ($showDeliveryReceipt == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/delivery-receipt/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Delivery Receipt</span>
                </a>
            @endif
            @if ($showJobOrder == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/job-order/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Job order</span>
                </a>
            @endif
            @if ($showOfficialReceipt == true)
                <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/official-receipt/{{ $dataTypeContent->txno }}">
                    <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Official Receipt</span>
                </a>
            @endif
        </div>
    </div>
</div>
{{ $transaction }}
