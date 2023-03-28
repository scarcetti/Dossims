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

    @if ( is_null($transaction->cashier) )
        <div style="margin: 30px 0 15px 0;">
            <input v-if="cashier.value" name="employee_id" :value="cashier.value.id" hidden/>
            <label class="control-label rr" for="name">Cashiered by</label>
            <multiselect
                v-model="cashier.value"
                {{-- @input="customer.value = []" --}}
                deselect-label="Remove"
                track-by="id"
                {{-- label="cashier.first_name" --}}
                :custom-label="customEmployeeLabel"
                placeholder="Select operating cashier"
                :options="cashier.options[0]"
                :searchable="true"
                :allow-empty="false"
            />
        </div>
    @else
        <div class="form-group  col-md-12" style="margin: 30px 0 15px 0;">
            <label class="control-label" for="name">Cashiered by</label>
            <h5>
                {{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}
            </h5>
        </div>
    @endif
</div>
