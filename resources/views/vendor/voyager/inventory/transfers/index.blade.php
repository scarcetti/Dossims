@php
    function branch_()
    {
        $user = Auth::user();
        $x = \App\Models\Branch::select('id')
            ->whereHas('branchEmployees.employee.user', function ($q) use ($user) {
                $q->where('id', $user->id);
            })
            ->first();
        return is_null($x) ? false : $x->id;
    }
@endphp
@extends('voyager::master')
@section('content')
    <div id="app">
        <section>
            @include('voyager::inventory.transfers.inbounds.create')
            @include('voyager::inventory.transfers.outbounds.create')

            <div style="margin: 20px 0 0 30px;">
                <span class="btn btn-primary" @click="createInboundButtonClicked()" readonly>Create Inbound</span>
                <span class="btn btn-primary" @click="createOutboundButtonClicked()" readonly>Create Outbound</span>
            </div>

            <div
                style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; margin-top: 20px;">
                <div class="col-md-7 col-xs-12 paginator_ containers_">
                    <div style="display: flex; flex-direction: row; width: 100%; margin: 0 0 15px 15px;">
                        <h4>
                            Inbounds
                        </h4>
                    </div>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Referrer</th>
                                <th>Arrival date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse($branch_products ?? '' as $item)
                            <tr style="border-top: solid #5c5c5c29 1px">
                                <td v-if="!activeBranch.id">{{ $item->branch->name }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @empty --}}
                            <tr>
                                <td colspan="4">No record</td>
                            </tr>
                            {{-- @endforelse --}}
                        </tbody>
                    </table>
                    <br><br>
                    {{-- {{ $branch_products ?? ''->links() }} --}}
                </div>
                <div class="col-md-5 col-xs-12 paginator_ containers_">
                    <div style="display: flex; flex-direction: row; width: 100%; margin: 0 0 15px 15px;">
                        <h4>
                            Outbounds
                        </h4>
                    </div>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Referrer</th>
                                <th>Arrival date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse($branch_products ?? '' as $item)
                            <tr style="border-top: solid #5c5c5c29 1px">
                                <td v-if="!activeBranch.id">{{ $item->branch->name }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @empty --}}
                            <tr>
                                <td colspan="3">No record</td>
                            </tr>
                            {{-- @endforelse --}}
                        </tbody>
                    </table>
                    <br><br>
                    {{-- {{ $branch_products ?? ''->links() }} --}}
                </div>
            </div>
        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory-transfers.css') }}">

    <script>
        var app = new Vue({
            el: '#app',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data() {
                return {
                    activeBranch: [],
                    branches: {!! $branches ?? '' !!},
                    inboundStocks: {!! $branch_stocks ?? '' !!},
                    outboundStocks: {!! $branch_stocks ?? '' !!},
                    confirmInboundStatus: false,
                    confirmInboundList: [],
                    confirmOutboundStatus: false,
                    confirmOutboundList: [],
                }
            },
            methods: {
                createInboundButtonClicked() {
                    this.reset_inbounds()
                    $(`#createInboundDialog`).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                createOutboundButtonClicked() {
                    this.reset_outbounds()
                    $(`#createOutboundDialog`).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                initialInboundStock() {
                    const x = {
                        branch_product_id: '',
                        quantity: 1,
                        linear_meters: 1,
                        tbd: 1,
                    }
                    this.inboundStocks = [x]
                },
                initialOutboundStock() {
                    const x = {
                        branch_product_id: '',
                        quantity: 1,
                        linear_meters: 1,
                        tbd: 1,
                    }
                    this.outboundStocks = [x]
                },
                reset_inbounds() {
                    const inputs = document.querySelectorAll('.inbound-stocks input')
                    inputs.forEach(input => {
                        input.value = ''
                    })

                    const tableRows = document.querySelectorAll('.inbound-stocks tr')
                    tableRows.forEach(input => {
                        input.classList.remove('error')
                    })
                    this.confirmInboundStatus = false
                },
                reset_outbounds() {
                    const inputs = document.querySelectorAll('.outbound-stocks input')
                    inputs.forEach(input => {
                        input.value = ''
                    })

                    const tableRows = document.querySelectorAll('.outbound-stocks tr')
                    tableRows.forEach(input => {
                        input.classList.remove('error')
                    })
                    this.confirmOutboundStatus = false
                },
                stock_validate(tableClass, branchProductId) {
                    const query = `table.${tableClass} tr.qty_validate_${branchProductId} input`
                    const elements = document.querySelectorAll(query)

                    let values = []
                    elements.forEach(element => {
                        const minValue = parseInt(element.min);
                        const maxValue = parseInt(element.max);
                        const currentValue = parseInt(element.value);

                        values.push(currentValue)

                        if (elements.length == 1) {
                            if (currentValue < minValue || currentValue > maxValue) {
                                document.querySelector(`table.${tableClass} tr.qty_validate_${branchProductId}`).classList.add(
                                    "error");
                            } else {
                                document.querySelector(`table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .remove("error");
                            }
                        } else if (elements.length == 2) {
                            const x = parseInt(elements[0].value)
                            const y = parseInt(elements[1].value)
                            const total = x * y
                            if ((x < 0 || y < 0) || (total < minValue || total > maxValue)) {
                                document.querySelector(`table.${tableClass} tr.qty_validate_${branchProductId}`).classList.add(
                                    "error");
                            } else {
                                document.querySelector(`table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .remove("error");
                            }
                        }
                        // const add_err = currentValue < minValue || currentValue > maxValue
                        // this.handle_error(query, add_err)
                    })
                    console.log(values)
                },
                createInbound() {
                    this.confirmInboundList = []
                    const hasValues = this.inboundStocks.filter(item => item.hasOwnProperty('pcs') || item.hasOwnProperty('meters'))
                    hasValues.forEach(item => {
                        this.confirmInboundList.push({
                            'id': item.id,
                            'name': item.product.name,
                            'pcs': item.pcs ? item.pcs : null,
                            'meters': item.meters ? item.meters : null,
                        })
                    })
                    console.log([this.confirmInboundList, this.confirmOutboundList])
                    this.confirmInboundStatus = true
                },
                confirmInbounds() {
                    this.confirmInboundStatus = false
                    alert('Inbounds created!')
                    window.location.reload()
                },
                createOutbound() {
                    this.confirmOutboundList = []
                    const hasValues = this.outboundStocks.filter(item => item.hasOwnProperty('pcs') || item.hasOwnProperty('meters'))
                    hasValues.forEach(item => {
                        this.confirmOutboundList.push({
                            'id': item.id,
                            'name': item.product.name,
                            'pcs': item.pcs ? item.pcs : null,
                            'meters': item.meters ? item.meters : null,
                        })
                    })
                    console.log([this.confirmInboundList, this.confirmOutboundList])
                    this.confirmOutboundStatus = true
                },
                confirmOutbounds() {
                    this.confirmOutboundStatus = false
                    alert('Outbounds created!')
                    window.location.reload()
                },
            },
            created() {
                // this.initialInboundStock()
            }
        })
    </script>
@endsection
