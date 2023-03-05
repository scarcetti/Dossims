<div v-if="transactionItem" class="payment_container">
    <div v-if="value[0].transaction.status == 'procuring'" class="total_container">
        <h4>Grand total</h4>
        <h2 style="font-weight: bold;">@{{ grandTotal }}</h2>
    </div>
    
    <div v-if="value[0].transaction.status == 'pending'">
        <span class="btn btn-primary" @click="paymentButtonClicked()" readonly>Add payment</span>

        <div class="modal fade" id="paymentDialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
            <div class="modal-success-dialog modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                <div class="modal-content">
                    <div class="modal-header" style="display: flex; align-items: center;">
                        <h5 class="modal-title" id="dialogLabel">Add payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-top: 0px !important; padding-left: 5%; padding-right: 5%; max-height: 70vh;">

                        <div class="total_container">
                            <div>
                                {{-- @{{ paymentType }} --}}
                                <span>Products total</span>
                                <h4 v-if="!paymentType">₱&nbsp;@{{ productsTotal.toFixed(2) }}</h4>
                                <h4 v-else-if="paymentType.id === 1">₱&nbsp;<s>@{{ (productsTotal * 2).toFixed(2) }}</s>&nbsp;@{{ productsTotal.toFixed(2) }}</h4>
                                <h4 v-else>₱&nbsp;@{{ productsTotal.toFixed(2) }}</h4>
                            </div>
                            <div>
                                <span>Transport total</span>
                                <h4>₱ @{{ shippingTotal.toFixed(2) }}</h4>
                            </div>
                            <div>
                                <h4>Grand total</h4>
                                <h2>@{{ grandTotal }}</h2>
                            </div>
                        </div>
                        <div class="dropdowns">
                            <div style="margin: 30px 0 15px 0;">
                                <input v-if="paymentType" name="payment_type_id" :value="paymentType.id" hidden/>
                                <multiselect
                                    v-model="paymentType"
                                    @input="paymentTypeChanged()"
                                    deselect-label="Can't remove this value"
                                    track-by="name"
                                    label="name"
                                    placeholder="Payment type"
                                    :options="paymentTypes"
                                    :searchable="false"
                                    :allow-empty="false"
                                />
                            </div>
                            <div style="margin: 30px 0 15px 0;">
                                <input v-if="paymentMethod" name="payment_type_id" :value="paymentMethod.id" hidden/>
                                <multiselect
                                    v-model="paymentMethod"
                                    @input="paymentTypeChanged()"
                                    deselect-label="Can't remove this value"
                                    track-by="name"
                                    label="name"
                                    placeholder="Payment type"
                                    :options="paymentMethods"
                                    :searchable="false"
                                    :allow-empty="false"
                                />
                            </div>
                            <div class="form-group  col-md-12" style="padding: 0;">

                                <label class="control-label" for="name">Amount tendered</label>
                                <input
                                    name="amount_tendered"
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    style="margin: 0 0 6px 0"
                                >
                            </div>
                        </div>
                        <div v-if="paymentType" style="text-align-last: end;">
                            <span class="btn btn-primary" @click="submitForm(paymentType.id)" readonly>
                                @{{ paymentType.id === 2 ? 'Print Official Receipt' : 'Print Charge Invoice' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>