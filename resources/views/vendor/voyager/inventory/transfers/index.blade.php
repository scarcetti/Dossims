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

            <div style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; margin-top: 20px;">
                <div class="col-md-7 col-xs-12 paginator_ containers_">
                    <div style="display: flex; flex-direction: row; width: 100%; margin: 0 0 15px 15px;">
                        <h4>
                            Inbounds
                        </h4>
                    </div>
                    <table style="width: 100%;">
                        <thead>
                            <tr />
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
                            <tr />
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
                }
            },
            methods: {
                createInboundButtonClicked() {
                    $(`#createInboundDialog`).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                createOutboundButtonClicked() {
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
            },
            created() {
                // this.initialInboundStock()
            }
        })
    </script>
@endsection
