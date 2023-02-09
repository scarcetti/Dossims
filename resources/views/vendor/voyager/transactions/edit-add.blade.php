@extends('voyager::bread.edit-add')
@section('submit-buttons')
    @parent
    <style>
        .cartContainer {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: nowrap;
        }

        .cartContainer > div > div{
            margin: 10px;
            display: flex;
            flex-direction: column;
        }

        .b_ * {
            border: solid 1px red;
        }
    </style>
@endsection
@section('submit-buttons')
    @include('common.alert')
    <div id="app" style="margin: 0 15px;">
       {{-- {!! $transaction_item ?? '' !!} --}}
        <div>
            <multiselect
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options="branchProducts"
                :multiple="true"
                {{-- :taggable="true" --}}
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
                    <input :name="`item-${item.product_id}-price`" :value="item.product.price" hidden />
                    <div :class="`cartContainer item-${item.product_id}`">
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
                                {{-- <h4 style="margin: 0">@{{ item.product.price }}</h4> --}}
                                <input
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
                                <textarea :name="`item-${item.product_id}-note`" rows="4" cols="50" placeholder="Write note here."></textarea>
                            </div>
                        </div>
                        <div {{-- style="margin-left: auto;" --}}>
                            <h5>Stock: </h5>
                            <h4> @{{ item.quantity }} </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <input v-if="paymentType" name="payment_type_id" :value="paymentType.id" hidden />
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
                    transactionItem: `{!! $transaction_item ?? '' !!}`,
                    branchProducts: {!! $branch_products ?? '' !!},
                    paymentType: null,
                    paymentTypes: {!! $payment_types ?? '' !!},
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
                    if(this.transactionItem) {
                        console.log(this.value)
                        console.log(JSON.parse(this.transactionItem))
                        // this.value = JSON.parse(this.transactionItem)
                    }
                }
            },
            created() {
                this.getUpdateValue()
            }
        })
    </script>
@endsection