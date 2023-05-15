@extends('voyager::master')
@section('content')
    <div id="app" style="margin: 0 15px">
        <h1 class="page-title"><i></i>
            Balance settlement
        </h1>
        <section class="panel cart payment_container">
            {{-- asdasd --}}
            {{-- {{ $balances }} --}}

            <div class="total_container" style="width: 60%">
                {{-- <div>
                    @{{ paymentType }}
                    <span>Products total</span>
                    <h4>asdasdad</h4>
                </div>
                <div>
                    <span>Delivery total</span>
                    <h4>₱ 123123</h4>
                </div> --}}
                <span class="logged_employee_" hidden>
                    {!! $logged_employee ?? '' !!}
                </span>
                <div>
                    <h4>Remaining balance</h4>
                    <h3>₱&nbsp;@{{ valueFixed( balance ) }}</h3>
                </div>
                <div v-show="payload.amount_tendered >= balance">
                    <span style="font-weight: bold;">Change</span>
                    <h4 style="font-weight: bold;">₱&nbsp;@{{ valueFixed( payload.amount_tendered - balance ) }}</h4>
                </div>
                <div style="margin: 30px 0 15px 0;">
                    <multiselect
                        v-model="payload.payment_method"
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
                <div v-if="payload.payment_method && payload.payment_method.id === 1" class="form-group  col-md-12" style="padding: 0;">
                    <label class="control-label" for="name">Amount tendered</label>
                    <input
                    v-model="payload.amount_tendered"
                    name="amount_tendered"
                    class="form-control"
                    type="number"
                    min="0"
                    style="margin: 0 0 6px 0"
                    >
                </div>
                <div style="margin: 30px 0 15px 0;">
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
                <div v-if="(payload.payment_method && payload.payment_method.id != 1) || payload.amount_tendered">
                    <span class="btn btn-primary" @click="addBilling()" readonly>
                        Print Official Receipt
                    </span>
                </div>
            </div>
            <center>
                <a class="btn btn-dark" href="{{ ENV('APP_URL') }}/admin/balances/" readonly>Return</a>
            </center>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/transactions.css') }}">

    <script>
        var app = new Vue({
            el: '#app',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data() {
                return {
                    payload: {
                        payment_method: null,
                        amount_tendered: 0,
                    },
                    balance: {!! $balances->outstanding_balance ?? '' !!},
                    paymentMethods: {!! $payment_methods ?? '' !!},
                    cashier: {
                        value: null,
                        options: [{!! $branch_employees ?? '' !!}]
                    },
                    balances_: {!! $balances ?? '' !!},
                }
            },
            methods: {
                paymentTypeChanged() {
                    if(this.payload.payment_method.id === 1) {
                        this.payload.amount_tendered = 0
                    }
                    else {
                        this.payload.amount_tendered = this.balance
                    }
                },
                valueFixed(x) {
                    return parseFloat(x).toFixed(2)
                },
                addBilling() {
                    this.payload.grand_total = this.balance
                    this.payload.cashier_id = this.cashier.value ? this.cashier.value.id : null
                    this.payload.balances_ = this.balances_

                    console.log(this.payload)

                    axios.post(`${window.location.origin}/admin/balances/settle`, this.payload)
                        .then(response => {
                            alert('Transaction completed!')
                            location.href = `${location.origin}/admin`
                        })
                        .catch(x => {
                            const y = Object.keys(x.response.data.errors)
                            for (let key of y) {
                                alert(x.response.data.errors[key][0])
                                break
                            }
                        })
                },
                customEmployeeLabel({employee}) {
                    // return employee
                    return `${employee.first_name} ${employee.last_name}`
                },
                getLoggedUser() {
                    const employees = document.querySelector('span.logged_employee_').innerHTML
                    const pattern = /^\s*$/g;

                    if(!pattern.test(employees)) {
                        this.cashier.value = JSON.parse(employees)
                    }
                },
            },
            created() {
                this.getLoggedUser()
            }
        })
    </script>
@endsection
