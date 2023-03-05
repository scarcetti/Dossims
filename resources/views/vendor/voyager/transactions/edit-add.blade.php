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
            @{{ value }}

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
                    <span v-on:click="addEmptyCartItem()" class="btn btn-warning edit" style="display: flex; flex-direction: column;">
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
                    cart: [],
                    productsTotal: '----',
                    shippingTotal: 0.00,
                    grandTotal: '----',
                    transactionItem: false,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
                    paymentMethod: null,
                    paymentMethods: {!! $payment_methods ?? '' !!},
                    cartSubtotals: [],
                    cbNote3: 'When switch is green, discounts are applied per item. Otherwise, discount is applied on the subtotal',
                }
            },
            methods: {
                addEmptyCartItem() {
                    this.value.push({})
                },
                cartItemSelect(x) {
                    console.log(x)
                },
                valueChanged(qtyQuery, price, index) {
                    const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value
                    const tbdVal = document.querySelector(`[name=${qtyQuery}-tbd]`).value
                    console.log([qtyQuery, price, index])

                    if(Number(qtyVal) < 1) {
                        if(window.confirm('Remove item from list?')) {
                            this.value.splice(index, 1)
                        }
                        else {
                            const qtyVal = document.querySelector(`[name=${qtyQuery}-quantity]`).value = 1
                            document.querySelector(`.cartContainer .subtotal.${qtyQuery}`).innerHTML = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal)).toFixed(2)
                        }
                    }
                    else {
                        document.querySelector(`.cartContainer .subtotal.${qtyQuery}`).innerHTML = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal)).toFixed(2)
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
                            total += parseFloat(item.discount_value) :
                            total += (parseFloat(item.price_at_purchase) * item.quantity)
                    })



                    if(this.paymentType) {
                        if(this.paymentType.id === 1) {
                            total = total / 2
                            console.log(total)
                        }
                    }

                    this.productsTotal = total
                    // this.productsTotal = `₱ ${total.toFixed(2)}`
                    this.grandTotal = `₱ ${(total - this.shippingTotal).toFixed(2)}`
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
                }
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