@extends('voyager::bread.edit-add')
@section('submit-buttons')
    @include('common.alert')
    <div id="app" style="margin: 0 15px;">
        <span class="txns_" hidden>
            {!! $transaction_item ?? '' !!}
        </span>
        <div>
            {{-- <multiselect
                v-if="!transactionItem"
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options="branchProducts"
                :multiple="true"
            ></multiselect> --}}{{-- 
            <multiselect
                v-else
                disabled
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options="branchProducts"
                :multiple="true"
            ></multiselect> --}}
            {{-- @{{ value }} --}}

            <div class="cartItemContainer">
                <div
                    v-for="(item, index) in value"
                    :key="index"
                >

                    @if( isset($transaction_item) )
                        @include('voyager::transactions.edit-add-modules.viewing')
                    @else
                        @include('voyager::transactions.edit-add-modules.quotation')
                    @endif
                </div>
                @if( !isset($transaction_item) )
                    <span v-on:click="addEmptyCartItem()" class="addItemBtn btn btn-warning edit" style="display: flex; flex-direction: column;">
                        <i class="voyager-plus"></i> Add new item
                    </span>
                @endif

                @include('voyager::transactions.edit-add-modules.totals')
            </div>
        </div>

        
    </div>
    <br>
    @if( count($dataTypeContent->toArray()) == 0 )
        @parent
    @endif
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
                    productsTotal: '----',
                    grandTotal: '----',
                    grandTotal_: 0,
                    transactionItem: false,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
                    paymentMethod: null,
                    paymentMethods: {!! $payment_methods ?? '' !!},
                    cartSubtotals: [],
                    cbNote3: 'When switch is green, discounts are applied per item. Otherwise, discount is applied on the subtotal',
                    deliveryFeeDialog: false,
                    deliveryFees: {
                        outisde: false,
                        long: false,
                        distance: 1,
                        shippingTotal: 0.00,
                    }
                }
            },
            methods: {
                addEmptyCartItem() {
                    this.value.push({})
                },
                cartItemSelect(qtyQuery, price, index) {
                    setTimeout(()=>{
                        document.querySelector(`[name=${qtyQuery}-quantity]`).value = 1
                        document.querySelector(`[name=${qtyQuery}-tbd]`).value = 1

                        const linearMeter = document.querySelector(`[name=${qtyQuery}-linear-meters]`)
                        if(linearMeter) {
                            linearMeter.value = 1
                        }

                        this.valueChanged(qtyQuery, price, index)
                    }, 50)
                },
                valueChanged(qtyQuery, price, index) {
                    const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value
                    const tbdVal = document.querySelector(`[name=${qtyQuery}-tbd]`).value
                    const x = document.querySelector(`[name=${qtyQuery}-linear-meters]`)
                    let linearMeter = 1
                    let result = '---'

                    if(x) {
                        linearMeter = x.value
                    }

                    if(Number(qtyVal) < 1) {
                        if(window.confirm('Remove item from list?')) {
                            this.value.splice(index, 1)
                        }
                        else {
                            const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value = 1
                            result = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal) * parseFloat(linearMeter)).toFixed(2)
                        }
                    }
                    else {
                        result = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal) * parseFloat(linearMeter)).toFixed(2)
                    }
                    document.querySelector(`.cartContainer .subtotal.${qtyQuery}`).innerHTML = result
                },
                getUpdateValue() {
                    const txnItems = document.querySelector('span.txns_').innerHTML
                    const pattern = /^\s*$/g;

                    if(!pattern.test(txnItems)) {
                        this.transactionItem = JSON.parse(txnItems)
                        this.value = this.transactionItem
                        this.getTotalValue()
                    }
                    else {
                        this.addEmptyCartItem()
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
                deliveryFeeModified() {

                    this.getTotalValue()
                },
                paymentTypeChanged() {
                    this.getTotalValue()
                },
                getTotalValue() {
                    let total = 0
                    this.value.forEach(item => {
                        item.discount_value ?
                            total += parseFloat(item.discount_value) * (item.linear_meters ? item.linear_meters : 1) :
                            total += (parseFloat(item.price_at_purchase) * item.quantity) * (item.linear_meters ? item.linear_meters : 1)
                    })



                    if(this.paymentType) {
                        if(this.paymentType.id === 1) {
                            total = total / 2
                            console.log(total)
                        }
                    }

                    this.productsTotal = total
                    // this.productsTotal = `₱ ${total.toFixed(2)}`
                    this.grandTotal = `₱ ${(total + this.deliveryFees.shippingTotal).toFixed(2)}`
                    this.grandTotal_ = (total + this.deliveryFees.shippingTotal).toFixed(2)
                },
                disableSubmitOnFieldsEnter() {
                    $('form.form-edit-add').keypress(
                      function(event){
                        if (event.which == '13') {
                          event.preventDefault();
                        }
                    })
                },
                paymentButtonClicked() {
                    $(`#paymentDialog`).modal({backdrop: 'static', keyboard: false});
                },
                submitForm(submitType) {
                    // submitType: 1 = downpayment, 2 = full
                    $('form').submit()
                },
                deliveryFeeInfoToggle( shown ) {
                    const initial = {
                            outisde: false,
                            long: false,
                            distance: 1,
                            shippingTotal: 0.00,
                        }

                    this.deliveryFeeDialog = shown

                    if( !shown ) {
                        this.deliveryFees = Object.assign({}, this.deliveryFees, initial)
                        this.getTotalValue()
                    }
                    else {
                        this.deliveryFeeToggles()
                    }
                },
                deliveryFeeToggles() {
                    setTimeout(() => {
                        this.deliveryFees.outisde = $('input.outsideBarnagay')[0].checked
                        this.deliveryFees.long = $('input.longOrder')[0].checked

                        if( !this.deliveryFees.outisde ) {
                            this.deliveryFees.distance = 1

                            this.deliveryFees.shippingTotal = this.deliveryFees.long ? 1000 : 500
                        }
                        else {
                            this.deliveryFees.shippingTotal = (this.deliveryFees.long ? 1000 : 500) + (100 * Number(this.deliveryFees.distance))
                        }

                        this.getTotalValue()
                    }, 10)
                },
            },
            created() {
                this.disableSubmitOnFieldsEnter()
                this.getUpdateValue()


                this.hideElements()
                // this.disableElements()
            }
        })
    </script>
@endsection