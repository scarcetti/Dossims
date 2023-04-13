@extends('voyager::master')
@section('content')
    <div id="app" style="margin: 0 15px;">
        <h1 class="page-title">
            <i class=""></i>
            @if( isset($transaction) )
                Discount and Billing
            @else
                Create Quotation
            @endif
        </h1>
        {{-- @{{form}} --}}
        {{-- @{{value}} --}}
        <section class="panel cart">
            @if( isset($transaction) )
                @include('voyager::transactions.edit-add-modules.heading.billing')
            @else
                @include('voyager::transactions.edit-add-modules.heading.create')
            @endif
            <span class="txn_" hidden>
                {!! $transaction ?? '' !!}
            </span>
            <div v-if="(customer.value && !customer.value.balance) || transaction.transaction_items">
                <div class="cartItemContainer">
                    <div
                        v-for="(item, index) in value"
                        :key="index"
                    >
                        @if( isset($transaction) )
                            @include('voyager::transactions.edit-add-modules.viewing')
                        @else
                            @include('voyager::transactions.edit-add-modules.quotation')
                        @endif
                    </div>
                    @if( !isset($transaction) )
                        <span v-if="value[value.length - 1].selection" v-on:click="addEmptyCartItem()" class="addItemBtn btn btn-warning edit" style="display: flex; flex-direction: column;">
                            <i class="voyager-plus"></i> Add new item
                        </span>
                    @endif
                    @include('voyager::transactions.edit-add-modules.totals')
                </div>
            </div>
            <br>
            @if( count($dataTypeContent->toArray()) == 0 )
                <span v-if="value[0].selection && customer.value && !customer.value.balance">
                    <button @click="createQuotation" type="submit" class="btn btn-primary save">Save</button>
                    <i style="color: red;">Fields with * are required</i>
                </span>
            @endif
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/transactions.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="module">
        var app = new Vue({
            el: '#app',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data () {
                return {
                    value: [],
                    productsTotal: null,
                    grandTotal: '----',
                    grandTotal_: 0,
                    transaction: {},
                    transactionItem: false,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
                    paymentMethod: null,
                    paymentMethods: {!! $payment_methods ?? '' !!},
                    downpaymentAmount: null,
                    amountTendered: null,
                    cartSubtotals: [],
                    cbNote3: 'When switch is green, discounts are applied per item. Otherwise, discount is applied on the subtotal',
                    deliveryFeeDialog: false,
                    deliveryFees: {
                        outside: false,
                        long: false,
                        distance: 1,
                        shippingTotal: 0.00,
                    },
                    // create
                    customer: {
                        value: null,
                        options: [{!! $customers ?? '' !!}]
                    },
                    businessCustomer: {
                        value: null,
                        options: [{!! $business_customers ?? '' !!}]
                    },
                    employee: {
                        value: null,
                        options: [{!! $branch_employees ?? '' !!}]
                    },
                    cashier: {
                        value: null,
                        options: [{!! $branch_employees ?? '' !!}]
                    },
                    form: {},
                    // create
                }
            },
            methods: {
                createQuotation() {
                    const payload = {
                        customer_id: this.customer.value && this.customer.value.id,
                        business_customer_id: this.businessCustomer.value && this.businessCustomer.value.id,
                        employee_id: this.employee.value && this.employee.value.employee_id,
                        cart: this.value,
                    }
                    console.log(payload)
                    axios.post(`${window.location.origin}/admin/transaction/create`, payload)
                        .then(response => {
                            window.location.reload()
                        })
                        .catch(x => {
                            const y = Object.keys(x.response.data.errors)
                            for (let key of y) {
                                alert(x.response.data.errors[key][0])
                                break
                            }
                        })
                },
                addBilling() {
                    const tendered = this.amountTendered ? this.amountTendered : this.downpaymentAmount
                    const balance = this.downpaymentAmount ? parseFloat(this.productsTotal) + parseFloat(this.grandTotal_) - parseFloat(tendered)   : null

                    const payload = {
                            payment: {
                                payment_type_id: this.paymentType.id,
                                payment_method_id: this.paymentMethod.id,
                                downpayment_amount: this.downpaymentAmount,
                                amount_tendered: tendered,
                                balance: balance,
                                grand_total: this.grandTotal_,
                                customer_id: this.transaction.customer_id,
                                business_customer_id: this.transaction.business_customer_id,
                            },
                            cart: this.value,
                            txid: this.transaction.id,
                            cashier_id: this.cashier.value && this.cashier.value.employee_id,
                            delivery_fee: this.deliveryFees,
                        }

                    axios.post(`${window.location.origin}/admin/transaction/billing`, payload)
                        .then(response => {
                            window.location.reload()
                            // console.log(response.data)
                        })
                        .catch(x => {
                            const y = Object.keys(x.response.data.errors)
                            for (let key of y) {
                                alert(x.response.data.errors[key][0])
                                break
                            }
                        })
                },
                customNameLabel({first_name, last_name, contact_no}) {
                    if(contact_no) {
                        return `${first_name} ${last_name} - ${contact_no}`
                    }
                    else {
                        return `${first_name} ${last_name}`
                    }
                },
                customEmployeeLabel({employee}) {
                    // return employee
                    return `${employee.first_name} ${employee.last_name}`
                },
                addEmptyCartItem() {
                    const cartInitial = {
                        branch_product_id: '',
                        quantity: 1,
                        linear_meters: 1,
                        tbd: 1,
                    }
                    this.value.push(cartInitial)
                },
                initialCartData() {
                    const cartInitial = {
                        branch_product_id: '',
                        quantity: 1,
                        linear_meters: 1,
                        tbd: 1,
                    }
                    this.value = [cartInitial]
                },
                cartItemSelect(qtyQuery, price, index, product_id) {
                    setTimeout(()=>{
                        // console.log(index)
                        document.querySelector(`.item-${index} [name=${qtyQuery}-quantity]`).value = 1
                        document.querySelector(`.item-${index} [name=${qtyQuery}-tbd]`).value = 1

                        this.value[index].quantity = 1
                        this.value[index].linear_meters = 1

                        const linearMeter = document.querySelector(`.item-${index} [name=${qtyQuery}-linear-meters]`)
                        if(linearMeter) {
                            linearMeter.value = 1
                        }

                        this.valueChanged(qtyQuery, price, index)

                        // update v-model
                        this.value[index].branch_product_id = product_id
                    }, 50)
                },
                valueChanged(qtyQuery, price, index) {
                    const qtyVal = document.querySelector(`.item-${index} [name=${qtyQuery}-quantity]`).value
                    const tbdVal = document.querySelector(`.item-${index} [name=${qtyQuery}-tbd]`).value
                    const x = document.querySelector(`.item-${index} [name=${qtyQuery}-linear-meters]`)
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
                            const qtyVal = document.querySelector(`.item-${index} [name=${qtyQuery}-quantity]`).value = 1
                            result = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal) * parseFloat(linearMeter)).toFixed(2)
                        }
                    }
                    else {
                        result = '₱ ' + (Number(qtyVal) * Number(price) * parseFloat(tbdVal) * parseFloat(linearMeter)).toFixed(2)
                    }
                    document.querySelector(`.cartContainer.item-${index} .subtotal.${qtyQuery}`).innerHTML = result
                },
                getUpdateValue() {
                    const txn = document.querySelector('span.txn_').innerHTML
                    const pattern = /^\s*$/g;

                    if(!pattern.test(txn)) {
                        this.transaction = JSON.parse(txn)
                        this.value = this.transaction.transaction_items

                        console.log(this.transaction)
                        this.getTotalValue()
                    }
                    else {
                        this.addEmptyCartItem()
                        this.initialCartData()
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
                    this.amountTendered = null
                    this.downpaymentAmount = null
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
                deliveryFeeInfoToggle( shown ) {
                    const initial = {
                            outside: false,
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
                        this.deliveryFees.outside = $('input.outsideBarnagay')[0].checked
                        this.deliveryFees.long = $('input.longOrder')[0].checked

                        if( !this.deliveryFees.outside ) {
                            this.deliveryFees.distance = 1

                            this.deliveryFees.shippingTotal = this.deliveryFees.long ? 1000 : 500
                        }
                        else {
                            this.deliveryFees.shippingTotal = (this.deliveryFees.long ? 1000 : 500) + (100 * Number(this.deliveryFees.distance))
                        }

                        this.getTotalValue()
                    }, 10)
                },
                deleteCartItem(index) {
                    this.value.splice(index, 1)
                    // console.log(this.value[index])
                },
                valueFixed(x) {
                    return parseFloat(x).toFixed(2)
                },
            },
            created() {
                this.disableSubmitOnFieldsEnter()
                this.getUpdateValue()

                // this.hideElements()
                // this.disableElements()
            }

        })
    </script>
@endsection
