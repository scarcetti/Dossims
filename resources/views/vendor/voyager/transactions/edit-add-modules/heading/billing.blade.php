<div>
    @if($transaction->customer)
        <div class="form-group  col-md-12" style="margin: 30px 0 15px 0;">
            <label class="control-label" for="name">Customer</label>
            <h5>
                {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }} {{ $transaction->customer->contact ? '- '. $transaction->customer : '' }}
            </h5>
        </div>
    @elseif($transaction->customer)
        <div class="form-group  col-md-12" style="margin: 30px 0 15px 0;">
            <label class="control-label" for="name">Business Customer</label>
            <h5>
                {{ $transaction->businessCustomer->name  }}
            </h5>
        </div>
    @endif
    {{-- <div class="form-group  col-md-12" style="margin: 30px 0 15px 0;">
        <label class="control-label" for="name">Employee</label>
        <h5>
            {{ $transaction->employee->first_name . ' ' . $transaction->employee->last_name }}
        </h5>
    </div> --}}
</div>
