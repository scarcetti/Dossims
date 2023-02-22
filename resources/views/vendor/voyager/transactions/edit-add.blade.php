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
                {{-- @{{ discountKey }} --}}
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
                    <input v-else :name="`item-${item.id}-price`" :value="item.price_at_purchase" hidden />

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
                                <small>@{{ item.product.measurement_unit.name }}: </small>
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
                            <h4> @{{ item.quantity }} @{{ item.product.measurement_unit.name }}</h4>
                        </div>
                    </div>

{{--  UP module for create =============================== DOWN module for edit  --}}

{{-- 
        !!! NOTE FOR FUTURE SELF ON EDIT !!!
        
        module for create using branch_products.id while on update mode using transaction_items.id for reference
 --}}

                    <div v-else :class="`cartContainer item-${item.id}`">
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
                                <small>@{{ item.branch_product.product.measurement_unit.name }}: </small>
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
                                <span v-if="item.discount_value" style="display: flex;">
                                    <h4 class="subtotal" style="color:#d5d5d5; margin: 0"><s>₱ @{{ (item.price_at_purchase * item.quantity).toFixed(2) }}</s></h4>&nbsp;&nbsp;&nbsp;
                                    <h4 class="subtotal" style="margin: 0">₱ @{{ item.discount_value }}</h4>
                                </span>
                                <span v-else style="display: flex;">
                                    <h4 class="subtotal" style="margin: 0">₱ @{{ (item.price_at_purchase * item.quantity).toFixed(2) }}</h4>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div>
                                <small>Order note: </small>
                                <textarea readonly class="form-control" :name="`item-${item.id}-note`" rows="4" cols="50" placeholder="Write note here.">@{{item.job_order.note}}</textarea>
                            </div>
                        </div>
                        <div>
                            <span v-if="item.transaction.status == 'pending' && !item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">Add discount</span>
                            <span v-if="item.transaction.status == 'pending' && item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">View discount</span>
                            <span v-if="item.transaction.status == 'procuring' && item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">View discount</span>
                            <span v-if="item.transaction.status == 'procuring' && !item.discount" class="btn" readonly style="background: #cbc0b3; color: white;">Not discounted</span>
                        </div>
                        {{-- @{{item.discount}} --}}
                        <div class="modal fade" :id="`discountDialog${item.id}`" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
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
                                            <label class="control-label" for="name">Discount value <i style="color:red;">&nbsp;&nbsp;&nbsp;Discount has no effect when discount value is empty</i></label>
                                            <input
                                                v-if="item.discount"
                                                :name="`item-${item.id}-discount-value`"
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                v-model="item.discount.value"
                                                style="margin: 0 0 6px 0"
                                                readonly
                                            >
                                            <input
                                                v-else
                                                v-on:change="discountsModified(item, index)"
                                                v-on:input="discountsModified(item, index)"
                                                :name="`item-${item.id}-discount-value`"
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                style="margin: 0 0 6px 0"
                                            >
                                        </div>

                                        <div class="form-group  col-md-12 ">
                                            <h5>Type of discount</h5>
                                            <ul v-if="item.transaction.status == 'pending'" class="radio" v-on:click="discountsModified(item, index)">
                                                <li>
                                                    <input type="radio" :id="`item-${item.id}-option-type-fixed`" :name="`item-${item.id}-discount-type`" value="fixed">
                                                    <label :for="`item-${item.id}-option-type-fixed`">Fixed amount</label>
                                                    <div class="check"></div>
                                                </li>
                                                <li>
                                                    <input type="radio" :id="`item-${item.id}-option-type-percentage`" :name="`item-${item.id}-discount-type`" value="percentage">
                                                    <label :for="`item-${item.id}-option-type-percentage`">Percentage</label>
                                                    <div class="check"></div>
                                                </li>
                                            </ul>
                                            <ul v-if="item.transaction.status == 'procuring' && item.discount">
                                                <li><label>@{{ item.discount.fixed_amount ? 'Fixed amount' : 'By percentage' }}</label></li>
                                                <li><label>@{{ item.discount.per_item ? `Applied per ${item.branch_product.product.measurement_unit.name}` : 'Applied on the subtotal' }}</label></li>
                                            </ul>
                                        </div>
                                        <div v-if="item.transaction.status == 'pending'" style="margin: 0 10px">
                                            <h5>Per item <small><br>@{{ cbNote3 }}</small></h5>
                                            <label class="switch">
                                                <input :name="`item-${item.id}-discount-type-per-item`" type="checkbox" v-on:click="discountsModified(item, index)">
                                                <div class="slider round"></div>
                                            </label>
                                        </div>
                                        <div v-if="item.transaction.status == 'pending' && !item.discount" style="text-align-last: right;">
                                            <span v-on:click="removeDiscount(item.id, index)" class="btn btn-danger edit">Remove discount</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div v-if="transactionItem" class="payment_container">
            <div class="total_contaienr">
                <h4>Grand total</h4>
                <h2 style="font-weight: bold;">@{{ grandTotal }}</h2>
            </div>
            <div v-if="value[0].transaction.status != 'procuring'" class="dropdowns">
                <div style="margin: 30px 0 15px 0;">
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
                    grandTotal: '----',
                    transactionItem: false,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
                    cartSubtotals: [],
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
                            document.querySelector(`.cartContainer.${qtyQuery} .subtotal`).innerHTML = '₱ ' + (Number(qtyVal) * Number(price)).toFixed(2)
                        }
                    }
                    else {
                        document.querySelector(`.cartContainer.${qtyQuery} .subtotal`).innerHTML = '₱ ' + (Number(qtyVal) * Number(price)).toFixed(2)
                    }
                },
                getUpdateValue() {
                    const txnItems = document.querySelector('span.txns_').innerHTML
                    const pattern = /^\s*$/g;

                    if(!pattern.test(txnItems)) {
                        this.transactionItem = JSON.parse(txnItems)
                        this.value = this.transactionItem
                        this.getTotalValue()
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
                removeDiscount(item_id, cardIndex) {
                    const elements = document.querySelectorAll(`[name*="${item_id}-discount"]`)
                    elements[0].value = null

                    this.value[cardIndex].discount_value = null

                    this.getTotalValue()
                },
                discountsModified(cardValues, cardIndex) {
                    const elements = document.querySelectorAll(`[name*="${cardValues.id}-discount"]`)

                    let fields = {
                        value: parseFloat(elements[0].value),
                        isFixed: elements[1].checked,
                        isPercentage: elements[2].checked,
                        isPerItem: elements[3].checked,
                    }

                    if(fields.value && fields.isFixed || fields.isPercentage) {
                        this.$set(this.value[cardIndex], 'discount_value', this.getDiscountedSubtotal(cardValues, fields))
                    }

                    this.getTotalValue()
                },
                getDiscountedSubtotal(cardValues, fields) {
                    let x = 0
                    if(fields.isFixed && fields.isPerItem) {
                        x = ((parseFloat(cardValues.price_at_purchase) - fields.value) * cardValues.quantity)/* - (fields.value * cardValues.quantity)*/
                    }
                    else if(fields.isFixed && !fields.isPerItem) {
                        x = (parseFloat(cardValues.price_at_purchase) * cardValues.quantity) - fields.value
                    }
                    else if(fields.isPercentage && fields.isPerItem) {
                        x = (parseFloat(cardValues.price_at_purchase) * cardValues.quantity) - ((fields.value / 100) * (parseFloat(cardValues.price_at_purchase) * cardValues.quantity))
                    }
                    else if(fields.isPercentage && !fields.isPerItem) {
                        x = (parseFloat(cardValues.price_at_purchase) * cardValues.quantity) - ((fields.value / 100) * (parseFloat(cardValues.price_at_purchase) * cardValues.quantity))
                    }
                    return x.toFixed(2)
                },
                getTotalValue() {
                    let total = 0
                    this.value.forEach(item => {
                        item.discount_value ?
                            total += parseFloat(item.discount_value) :
                            total += (parseFloat(item.price_at_purchase) * item.quantity)
                    })
                    this.grandTotal = `₱ ${total.toFixed(2)}`
                }
            },
            created() {
                this.getUpdateValue()

                this.hideElements()
                // this.disableElements()
            }
        })
    </script>
@endsection