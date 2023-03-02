@extends('voyager::master')
@section('content')

{{-- {{ $orders }} --}}
<div id="app" row>
    <div class="col-md-12 paginator_ containers_" style="overflow: auto; max-height: 500px;">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 10px 10px; white-space: nowrap;">
                        Customer
                    </th>
                    <th style="padding: 10px 10px; white-space: nowrap;">
                        Cutting list placed at
                    </th>
                    <th style="padding: 10px 10px; white-space: nowrap;">
                        Procuring progress
                    </th>
                    <th class="text-right" style="padding: 10px 10px; white-space: nowrap;">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $item)
                    <tr style="border-top: solid #5c5c5c29 1px;">
                        <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                            {{
                            	is_null($item->customer) ?
                            		$item->businessCustomer->name :
                            		$item->customer->first_name . ' ' . $item->customer->last_name;
                            }}
                        </td>
                        <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                            {{ \Carbon\Carbon::parse($item->updated_at)->format('M d, Y h:i A'); }}
                        </td>  
                        <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                            {{-- {{ $item->jobOrder->note }} --}}
                            <i><small>[ PENDING_COUNT ] [ IN_PROGRESS_COUNT ] [ COMPLETED_COUNT ]</small></i>
                        </td>
                        <td style="padding: 0 10px; line-height: 30px; white-space: nowrap;">
                            {{-- <span class="btn btn-primary" @click="updateItemStatus()" readonly>Item prepared</span> --}}
                            <a href="{{ ENV('APP_URL') }}/admin/cutting-list/{{ $item->id }}" title="View" class="btn btn-sm btn-primary pull-right view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View Cutting list</span>
                            </a> 
                        </td>
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
        	updateItemStatus() {
        		console.log('btn clicked')
        	}
        },
    })
</script>
@endsection