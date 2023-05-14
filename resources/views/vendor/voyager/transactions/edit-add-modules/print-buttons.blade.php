<div class="payment_container">
    <div class="total_container">
        <h4>Download printables</h4>
        {{-- <h2 style="font-weight: bold;">smeagol</h2> --}}
        <div style="width: 100%">
            <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/charge-invoice/{{ $dataTypeContent->txno }}">
                <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Charge Invoice</span>
            </a>
            <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/cash-invoice/{{ $dataTypeContent->txno }}">
                <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Cash Invoice</span>
            </a>
            <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/delivery-receipt/{{ $dataTypeContent->txno }}">
                <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Delivery Receipt</span>
            </a>
            <a title="Remove Item" class="btn btn-sm btn-primary pull-right delete"  href="{{ env('APP_URL') }}/printouts/official-receipt/{{ $dataTypeContent->txno }}">
                <i class="voyager-download"></i> <span class="hidden-xs hidden-sm">Official Receipt</span>
            </a>
        </div>
    </div>
</div>
