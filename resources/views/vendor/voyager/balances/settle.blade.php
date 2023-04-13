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
                <div>
                    <h4>Remaining balance</h4>
                    <h3>{{ $balances->outstanding_balance }}</h3>
                </div>
                {{-- <div v-if="downpaymentAmount" :class="`${(downpaymentAmount - grandTotal_) >= 0 ? '' : 'err'}`">
                    <h4>Downpayment amount</h4>
                    <h2 style="border-top: unset;">₱ @{{ valueFixed(downpaymentAmount) }}</h2>
                </div>
                <div v-if="amountTendered" :class="`${(amountTendered - grandTotal_) >= 0 ? '' : 'err'}`">
                    <span>Amount tendered</span>
                    <h4>-&nbsp;&nbsp;&nbsp;₱@{{ valueFixed(amountTendered) }}</h4>
                </div>
                <div v-if="amountTendered && (amountTendered - grandTotal_) > 0">
                    <span style="font-weight: bold;">Change</span>
                    <h4 style="font-weight: bold;">₱@{{ valueFixed( amountTendered - grandTotal_ ) }}</h4>
                </div> --}}

            </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/transactions.css') }}">

    <script>
        var app = new Vue({
            el: '#app',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data() {
                return {

                }
            },
            methods: {

            }
        })
    </script>
@endsection
