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
    <style type="text/css">
        .swal2-container.swal2-center.swal2-backdrop-show {
            z-index: 9999999999 !important;
        }
    </style>
    <div id="app">
        <section>
            @include('voyager::inventory.transfers.inbounds.create')
            @include('voyager::inventory.transfers.outbounds.create')

            <div
                style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; margin-top: 20px;">
                <div class="col-md-6 col-xs-12 paginator_ containers_">
                    <div
                        style="display: flex; flex-direction: row; justify-content: space-between; width: 100%; margin: 0 0 15px 15px;">
                        <h4>
                            Inbounds
                        </h4>
                        <span class="btn btn-primary" @click="createInboundButtonClicked()" readonly
                            style="margin-left: 10px;">Create Inbound</span>
                    </div>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>From</th>
                                {{-- <th>Referrer</th> --}}
                                <th>Arrival date</th>
                                <th>Receiver</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inbounds ?? '' as $item)
                                <tr style="border-top: solid #5c5c5c29 1px">
                                    <td>{{ $item->sender->name ?? '---' }}</td>
                                    {{-- <td>{{ $item->referrer }}</td> --}}
                                    <td>{{ $item->arrival_date ?? '---' }}</td>
                                    <td>{{ $item->employee->full_name ?? '---' }}</td>
                                    <td>
                                        @if (is_null($item->arrival_date))
                                            <span class="btn btn-success"
                                                @click="inboundArrival({{ $item }})">Confirm Arrival</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No record</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br><br>
                    {{ $inbounds->links() }}
                </div>
                <div class="col-md-6 col-xs-12 paginator_ containers_">
                    <div
                        style="display: flex; flex-direction: row; justify-content: space-between; width: 100%; margin: 0 0 15px 15px;">
                        <h4>
                            Outbounds
                        </h4>
                        <span class="btn btn-primary" @click="createOutboundButtonClicked()" readonly
                            style="margin-left: 10px;">Create Outbound</span>
                    </div>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>To</th>
                                {{-- <th>Referrer</th> --}}
                                <th>Arrival date</th>
                                <th>Receiver</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outbounds ?? '' as $item)
                                <tr style="border-top: solid #5c5c5c29 1px">
                                    <td>{{ $item->receiver->name ?? '---' }}</td>
                                    {{-- <td>{{ $item->referrer }}</td> --}}
                                    <td>{{ $item->arrival_date ?? '---' }}</td>
                                    <td>{{ $item->employee->full_name ?? '---' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No record</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br><br>
                    {{ $outbounds->links() }}
                </div>
            </div>
        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
        integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>`
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory-transfers.css') }}">

    <script>
        var app = new Vue({
            el: '#app',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data() {
                return {
                    activeBranch: {},
                    branches: {!! $branches ?? '' !!},
                    inboundStocks: {!! $branch_stocks ?? '' !!},
                    outboundStocks: {!! $branch_stocks ?? '' !!},
                    inboundStocks_: [],
                    outboundStocks_: [],
                    confirmInboundStatus: false,
                    confirmInboundList: [],
                    confirmOutboundStatus: false,
                    confirmOutboundList: [],
                    inboundsForm: {},
                    outboundsForm: {},
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
                    this.inboundStocks_ = JSON.parse(JSON.stringify(this.inboundStocks))
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
                    this.outboundStocks_ = JSON.parse(JSON.stringify(this.inboundStocks))
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
                                document.querySelector(
                                        `table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .add(
                                        "error");
                            } else {
                                document.querySelector(
                                        `table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .remove("error");
                            }
                        } else if (elements.length == 2) {
                            const x = parseInt(elements[0].value)
                            const y = parseInt(elements[1].value)
                            const total = x * y
                            if ((x < 0 || y < 0) || (total < minValue || total > maxValue)) {
                                document.querySelector(
                                        `table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .add(
                                        "error");
                            } else {
                                document.querySelector(
                                        `table.${tableClass} tr.qty_validate_${branchProductId}`).classList
                                    .remove("error");
                            }
                        }
                        // const add_err = currentValue < minValue || currentValue > maxValue
                        // this.handle_error(query, add_err)
                    })
                    // console.log(values)
                },
                getMax() {
                    // console.log(123123)
                },
                createInboundDialog() {
                    this.confirmInboundList = []
                    const hasValues = this.inboundStocks_.filter(item => (item.hasOwnProperty('pcs') || item
                        .hasOwnProperty('meters')) && item.pcs > 0)
                    hasValues.forEach(item => {
                        this.confirmInboundList.push({
                            product_id: item.id,
                            name: item.product.name,
                            pcs: item.pcs ? item.pcs : null,
                            meters: item.meters ? item.meters : null,
                        })
                    })
                    // console.log([this.confirmInboundList, this.confirmOutboundList])
                    this.confirmInboundStatus = true
                },
                confirmInbounds() {
                    this.confirmInboundStatus = false
                    this.createTransfer('inbound')
                },
                createOutboundDialog() {
                    this.confirmOutboundList = []
                    const hasValues = this.outboundStocks_.filter(item => (item.hasOwnProperty('pcs') || item
                        .hasOwnProperty('meters') && Number(item.pcs) <= item.quantity))
                    hasValues.forEach(item => {
                        if (item.hasOwnProperty('meters')) {
                            if ((Number(item.meters) * Number(item.pcs)) <= item.quantity) {
                                this.confirmOutboundList.push({
                                    product_id: item.id,
                                    name: item.product.name,
                                    pcs: item.pcs ? item.pcs : null,
                                    meters: item.meters ? item.meters : null,
                                })
                            }
                        } else {
                            this.confirmOutboundList.push({
                                product_id: item.id,
                                name: item.product.name,
                                pcs: item.pcs ? item.pcs : null,
                                meters: item.meters ? item.meters : null,
                            })
                        }
                    })
                    // console.log([this.confirmInboundList, this.confirmOutboundList])
                    this.confirmOutboundStatus = true
                },
                confirmOutbounds() {
                    this.confirmOutboundStatus = false
                    this.createTransfer('outbound')
                },
                createTransfer(direction) {
                    const isInbound = direction == 'inbound'
                    const payload = {
                        direction: direction,
                        arrival_date: isInbound ? new Date().toISOString() : null,
                        referrer: isInbound ? this.inboundsForm.referrer : this.outboundsForm.referrer,
                        referrer_contact: isInbound ? this.inboundsForm.referrer_contact : this.outboundsForm
                            .referrer_contact,
                        distributor_id: isInbound ? null : null,
                        receiver_branch_id: isInbound ? {!! branch_() !!} : this.activeBranch.id,
                        sender_branch_id: isInbound ? null : {!! branch_() !!},
                        products: isInbound ? this.confirmInboundList : this.confirmOutboundList,
                    }

                    axios.post(`${window.location.origin}/admin/inventory/transfers/${direction}/create`, payload)
                        .then(response => {
                            Swal.fire({
                                title: 'Success!',
                                text: `${direction == 'inbound' ? 'Inbounds' : 'Outbounds'} created!`,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload()
                                }
                            })
                            // alert(`${direction == 'inbound' ? 'Inbounds' : 'Outbounds'} created!`)
                        })
                        .catch(x => {
                            const y = Object.keys(x.response.data.errors)
                            for (let key of y) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: `${x.response.data.errors[key][0]}`,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                })
                                break
                            }
                        })
                },
                inboundArrival(payload) {
                    axios.post(`${window.location.origin}/admin/inventory/transfers/inbound/confirm`, payload)
                        .then(response => {
                            Swal.fire({
                                title: 'Success!',
                                text: `Inbounds Arrival Confirmed.`,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload()
                                }
                            })
                        })
                        .catch(x => {
                            const y = Object.keys(x.response.data.errors)
                            for (let key of y) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: `${x.response.data.errors[key][0]}`,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                })
                                break
                            }
                        })
                },
            },
            created() {
                // this.initialInboundStock()
            }
        })
    </script>
@endsection
