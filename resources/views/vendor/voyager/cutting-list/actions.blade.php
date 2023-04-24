@php
    function is_admin()
    {
        return !in_array(Auth::user()->role->name, ['general_manager', 'branch_manager']);
    }
@endphp
@extends('voyager::master')
@section('content')
    {{-- {{ $txns->transactionItems }} --}}
    <div id="app" row>
        <div class="col-md-12 paginator_ containers_" style="overflow: auto; max-height: 500px;">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="padding: 10px 10px; white-space: nowrap;">
                            Product name
                        </th>
                        <th style="padding: 10px 10px; white-space: nowrap;">
                            Quantity / Measurement
                        </th>
                        <th style="padding: 10px 10px; white-space: nowrap;">
                            Additional Note
                        </th>
                        <th style="padding: 10px 10px; white-space: nowrap;">
                            Status
                        </th>
                        @if (is_admin())
                            <th class="text-right" style="padding: 10px 10px; white-space: nowrap;">
                                Action
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($txns->transactionItems as $item)
                        <tr style="border-top: solid #5c5c5c29 1px;">
                            <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                                {{ $item->branchProduct->product->name }}
                            </td>
                            <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                                {{ $item->quantity }} {{ $item->branchProduct->product->measurementUnit->name }}
                            </td>
                            <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                                {{ $item->jobOrder->note }}
                            </td>
                            <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                                {{ $item->jobOrder->status }}
                            </td>
                            @if (is_admin())
                                <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                                    @if ($item->jobOrder->status == 'completed')
                                        <span class="btn btn-success pull-right" readonly>Completed</span>
                                    @else
                                        <span class="btn btn-primary pull-right"
                                            @click="updateItemStatus({{ $item }})" readonly>Progress status</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr style="border-top: solid #5c5c5c29 1px; font-weight: bold; color: #979797;">
                            <td style="padding: 0 10px;">No record at this moment</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <br>
            {{-- {{ $containers->links() }} --}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cutting-list.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
        integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="module">
    var app = new Vue({
        el: '#app',
        /*components: {
            Multiselect: window.VueMultiselect.default,
        },*/
        data () {
            return {}
        },
        methods: {
        	updateItemStatus(item) {
                // console.log(item.job_order.status)
                let next = ''
                if(item.job_order.status == 'pending') next = `In progress`
                if(item.job_order.status == 'in progress') next = `Completed`

                let text = `Update status of "${item.branch_product.product.name}" to "${next}"?`;
                if (confirm(text) == true) {
                    // update query here
                    axios.patch(`${window.location.origin}/admin/cutting-list/update-status/${item.job_order.id}`)
                    .then(() => window.location.reload())
                }
        	},
        },
    })
</script>
@endsection
