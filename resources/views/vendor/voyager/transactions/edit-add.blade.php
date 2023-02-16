@extends('voyager::bread.edit-add')
@section('submit-buttons')
    @parent
@endsection
@section('submit-buttons')
    @include('common.alert')
    <div id="app" style="margin: 0 15px;">
        <span class="txns_" hidden>
            {!! $transaction_item ?? '' !!}
        </span>
        <div>
            <multiselect
                v-if="!transactionItem"
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options="branchProducts"
                :multiple="true"
            ></multiselect>
            <multiselect
                v-else
                disabled
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options="branchProducts"
                :multiple="true"
            ></multiselect>

            <div style="
                max-height: 500px;
                overflow: auto;
                margin-top: 20px;
            ">
                <div
                    v-for="(item, index) in value"
                    :key="index"
                    style="
                        border: 1px solid #e8e8e8;
                        border-radius: 5px;
                        padding: 8px;
                        margin: 0 0 20px 0;
                    "
                >
                    {{-- @{{ item }} --}}
                    <input v-if="item.product" :name="`item-${item.product_id}-price`" :value="item.product.price" hidden />
                    <input v-else :name="`item-${item.branch_product_id}-price`" :value="item.price_at_purchase" hidden />

                    <div v-if="item.product" :class="`cartContainer item-${item.product_id}`">
                        <div>
                            <div>
                                <small>Product name: </small>
                                <h4 style="margin: 0 0 10px 0">@{{ item.product_name }}</h4>
                            </div>
                            <div>
                                <small>Item price: </small>
                                <h4 style="margin: 0">₱ @{{ item.product.price }}</h4>
                            </div>
                        </div>
                        <div>
                            <div>
                                <small>Quantity: </small>
                                <input
                                    class="form-control"
                                    :v-model="`cart.${item.product_id}-quantityCount`"
                                    value="1"
                                    type="number"
                                    :name="`item-${item.product_id}-quantity`"
                                    min="0"
                                    :max="item.quantity"
                                    style="margin: 0 0 6px 0"
                                    v-on:change="valueChanged(`item-${item.product_id}`, item.product.price, index)"
                                >
                            </div>
                            <div>
                                <small>Subtotal: </small>
                                <h4 class="subtotal" style="margin: 0">₱ @{{ item.product.price }}</h4>
                            </div>
                        </div>
                        <div>
                            <div>
                                <small>Order note: </small>
                                <textarea class="form-control" :name="`item-${item.product_id}-note`" rows="4" cols="50" placeholder="Write note here."></textarea>
                            </div>
                        </div>
                        <div {{-- style="margin-left: auto;" --}}>
                            <h5>Stock: </h5>
                            <h4> @{{ item.quantity }} </h4>
                        </div>
                    </div>

{{--  UP module for create =============================== DOWN module for edit  --}}

                    <div v-else :class="`cartContainer item-${item.branch_product_id}`">
                        <div>
                            <div>
                                <small>Product name: </small>
                                <h4 style="margin: 0 0 10px 0">@{{ item.product_name }}</h4>
                            </div>
                            <div>
                                <small>Item price: </small>
                                <h4 style="margin: 0">₱ @{{ item.price_at_purchase }}</h4>
                            </div>
                        </div>
                        <div>
                            <div>
                                <small>Quantity: </small>
                                <input
                                    class="form-control"
                                    readonly
                                    :value="item.quantity"
                                    type="number"
                                    min="0"
                                    style="margin: 0 0 6px 0"
                                >
                            </div>
                            <div>
                                <small>Subtotal: </small>
                                <h4 class="subtotal" style="margin: 0">₱ @{{ item.price_at_purchase * item.quantity }}</h4>
                            </div>
                        </div>
                        <div>
                            <div>
                                <small>Order note: </small>
                                <textarea readonly class="form-control" :name="`item-${item.branch_product_id}-note`" rows="4" cols="50" placeholder="Write note here.">@{{item.job_order.note}}</textarea>
                            </div>
                        </div>
                        <div>
                            <span v-on:click="discountDialogShow(item.branch_product_id)" class="btn btn-primary save">Add discount</span>
                        </div>
                        <div class="modal fade" :id="`discountDialog${item.branch_product_id}`" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
                            <div class="modal-success-dialog modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                <div class="modal-content">
                                    <div class="modal-header" style="display: flex; align-items: center;">
                                        <h5 class="modal-title" id="dialogLabel">Set discounts for @{{ item.product_name }}</h5>
                                        <button type="button" {{-- @click="closeImage" --}} class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <hr style="margin: 0">
                                    <div class="modal-body" style="padding-top: 0px !important; padding-left: 5%; padding-right: 5%; max-height: 70vh;">
                                        <div class="form-group  col-md-12" style="padding: 0;">
                                            <label class="control-label" for="name">Discount value</label>
                                            <input
                                                :name="`item-${item.branch_product_id}-discount-value`"
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                style="margin: 0 0 6px 0"
                                            >
                                        </div>
                                        <div style="margin: 0 10px">
                                            <h5>Fixed amount</h5>
                                            <label class="switch">
                                                <input :name="`item-${item.branch_product_id}-discount-type-fixed-amount`" type="checkbox" {{-- v-on:click="searchFilterToggled(0)" --}}>
                                                <div class="slider round"></div>
                                            </label>
                                        </div>
                                        <div style="margin: 0 10px">
                                            <h5>Percentage</h5>
                                            <label class="switch">
                                                <input :name="`item-${item.branch_product_id}-discount-type-percentage`" type="checkbox" {{-- v-on:click="searchFilterToggled(0)" --}}>
                                                <div class="slider round"></div>
                                            </label>
                                        </div>
                                        <div style="margin: 0 10px">
                                            <h5>Per item <small><br>@{{ cbNote3 }}</small></h5>
                                            <label class="switch">
                                                <input :name="`item-${item.branch_product_id}-discount-type-per-item`" type="checkbox" {{-- v-on:click="searchFilterToggled(0)" --}}>
                                                <div class="slider round"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div v-if="transactionItem" style="margin: 30px 0 15px 0;">
            <input v-if="paymentType" name="payment_type_id" :value="paymentType.id" hidden/>
            <multiselect
                v-model="paymentType"
                deselect-label="Can't remove this value"
                track-by="name"
                label="name"
                placeholder="Payment type"
                :options="paymentTypes"
                :searchable="false"
                :allow-empty="false"
            />

        </div>
        <div v-if="transactionItem" class="form-group  col-md-12" style="padding: 0;">

            <label class="control-label" for="name">Amount tendered</label>
            <input
                name="amount-tendered"
                class="form-control"
                type="number"
                min="0"
                style="margin: 0 0 6px 0"
            >
        </div>
    </div>
    <br>
    @parent
@endsection
@section('javascript')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/transactions.css') }}">

    <script type="module">
        // import VueNumberInput from '@chenfengyuan/vue-number-input';
        // window.VueNumberInput = require('@chenfengyuan/vue-number-input');
        var app = new Vue({
            el: '#app',
            components: { 
                Multiselect: window.VueMultiselect.default,
                // VueNumberInput.name: VueNumberInput
            },
            data () {
                return {
                    value: [],
                    cart: [],
                    transactionItem: false,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
                    cbNote3: 'When switch is green, discounts are applied per item. Otherwise, discount is applied on the subtotal',
                }
            },
            methods: {
                valueChanged(qtyQuery, price, index) {
                    const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value
                    if(Number(qtyVal) < 1) {
                        if(window.confirm('Remove item from list?')) {
                            // alert(1)
                            this.value.splice(index, 1)
                        }
                        else {
                            const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value = 1
                            document.querySelector(`.cartContainer.${qtyQuery} .subtotal`).innerHTML = '₱ ' + String(Number(qtyVal) * Number(price))
                        }
                    }
                    else {
                        document.querySelector(`.cartContainer.${qtyQuery} .subtotal`).innerHTML = '₱ ' + String(Number(qtyVal) * Number(price))
                    }
                },
                getUpdateValue() {
                    const txnItems = document.querySelector('span.txns_').innerHTML
                    const pattern = /^\s*$/g;

                    if(!pattern.test(txnItems)) {
                        this.transactionItem = JSON.parse(txnItems)
                        this.value = this.transactionItem
                    }
                },
                hideElements() {
                    // Hide Status field
                    const element = document.querySelector('input.form-control[name="status"]').parentNode
                    element.style.display = 'none'
                },
                disableElements() {
                    if(this.transactionItem) {
                        document.querySelector('input.form-control[name="created_at"]').setAttribute("readonly", "readonly");
                    }
                },
                discountDialogShow(id) {
                    $(`#discountDialog${id}`).modal({backdrop: 'static', keyboard: false});
                },
            },
            created() {
                this.getUpdateValue()

                this.hideElements()
                this.disableElements()
            }
        })
    </script>
@endsection