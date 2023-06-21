

<div v-if="transaction.transaction_items" class="payment_container">
    <div v-if="transaction.status == 'procuring'" class="total_container">
        <h4>Grand total </h4>
            <h2 style="font-weight: bold;">@{{ grandTotal }}</h2>
    </div>
    <div v-if="transaction.status == 'waiting for payment'">
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
                                <span>Delivery total</span>
                                <h4>₱ @{{ deliveryFees.shippingTotal.toFixed(2) }}</h4>
                            </div>
                            <div>
                                <h4 v-if="paymentType">@{{ paymentType.id === 1 ? 'Minimum Payable' : 'Grand Total'}}</h4>
                                <h4 v-else>Grand total</h4>
                                <h2>@{{ grandTotal }}</h2>
                                <input type="text" name="grand_total" :value="grandTotal_" hidden>
                            </div>
                            <div v-if="downpaymentAmount" :class="`${(downpaymentAmount - grandTotal_) >= 0 ? '' : 'err'}`">
                                <h4>Downpayment amount</h4>
                                <h2 style="border-top: unset;">₱ @{{ valueFixed(downpaymentAmount) }}</h2>
                            </div>
                            <div v-if="amountTendered" :class="`${(amountTendered - grandTotal_) >= 0 ? '' : 'err'}`">
                                <span>Amount tendered</span>
                                <h4>-&nbsp;&nbsp;&nbsp;₱@{{ valueFixed(amountTendered) }}</h4>
                            </div>
                            <div v-if="amountTendered && (amountTendered - grandTotal_) > 0">
                                {{-- !(paymentMethod.id == 1 && paymentType.id == 1) --}}
                                <span style="font-weight: bold;">Change</span>
                                <h4 v-if="!(paymentMethod.id == 1 && paymentType.id == 1)" style="font-weight: bold;">₱@{{ valueFixed( amountTendered - grandTotal_ ) }}</h4>
                                <h4 v-else style="font-weight: bold;">₱@{{ valueFixed( amountTendered - downpaymentAmount ) }}</h4>
                            </div>
                            {{-- <div v-else>
                                <span style="font-weight: bold;">Change</span>
                                <h4 style="font-weight: bold;">₱@{{ valueFixed( downpaymentAmount - amountTendered ) }}</h4>
                            </div> --}}

                        </div>

                        <div v-if="deliveryFeeDialog" class="deliveryFeeContainer">
                            <input name="delivery_fee" :value="JSON.stringify(deliveryFees)" hidden>
                            <span class="btn btn-danger" @click="deliveryFeeInfoToggle(false)" readonly>Remove delivery fee</span>
                            <div style="display:flex; justify-content: space-evenly;">
                                <div style="margin-top: 10px;">
                                    <h5><small>Delivery outside barangay of the branch</small></h5>
                                    <label class="switch">
                                        <input class="outsideBarnagay" type="checkbox" v-on:click="deliveryFeeToggles()">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                                <div style="margin-top: 10px;">
                                    <h5><small>Items exceeding 5 meters (long)</small></h5>
                                    <label class="switch">
                                        <input class="longOrder" type="checkbox" v-on:click="deliveryFeeToggles()">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>
                            <div v-if="deliveryFees.outside == true" style="display: contents;">
                                <label class="control-label" for="name">Destination distance (km)</label>
                                <input
                                    v-model="deliveryFees.distance"
                                    class="distance"
                                    type="number"
                                    min="0"
                                    style="margin: 0 0 6px 0"
                                    @input="deliveryFeeToggles()"
                                >
                            </div>
                        </div>
                        <span v-else class="btn btn-primary" @click="deliveryFeeInfoToggle(true)" readonly>Add delivery fee</span>

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
                                <input v-if="paymentMethod" name="payment_method_id" :value="paymentMethod.id" hidden/>
                                <multiselect
                                    v-model="paymentMethod"
                                    @input="paymentTypeChanged()"
                                    deselect-label="Can't remove this value"
                                    track-by="name"
                                    label="name"
                                    placeholder="Payment method"
                                    :options="paymentMethods"
                                    :searchable="false"
                                    :allow-empty="false"
                                />
                            </div>
                            <div v-if="paymentType ? paymentType.id === 1 : false " class="form-group  col-md-12" style="padding: 0;">

                                <label class="control-label" for="name">Downpayment amount</label>
                                <input
                                    v-model="downpaymentAmount"
                                    name="downpayment_amount"
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    style="margin: 0 0 6px 0"
                                >
                            </div>
                            <div v-if="paymentMethod && paymentType ?  (paymentType.id==1 && paymentMethod.id != 1 ? false : true) : false" class="form-group  col-md-12" style="padding: 0;">

                                <label class="control-label" for="name">Amount tendered</label>
                                <input
                                v-model="amountTendered"
                                name="amount_tendered"
                                class="form-control"
                                type="number"
                                min="0"
                                style="margin: 0 0 6px 0"
                                >
                            </div>
                        </div>
                        <div v-if="paymentType && paymentMethod && (downpaymentAmount || amountTendered)" style="text-align-last: end;">
                            <span class="btn btn-danger" data-dismiss="modal" aria-label="Close" readonly>
                                {{-- @{{ paymentType.id === 2 ? 'Print Official Receipt' : 'Print Charge Invoice' }} --}}
                                Cancel
                            </span>
                            <span class="btn btn-primary" @click="addBilling()" readonly>
                                {{-- @{{ paymentType.id === 2 ? 'Print Official Receipt' : 'Print Charge Invoice' }} --}}
                                Add Payment
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
