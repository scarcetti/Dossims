@extends('voyager::master')
@section('content')
    <div id="app">
        <section>
            <div class="containers_" style="justify-items: start;">
                <h4>
                    Create outbound
                </h4>
                {{-- <form id="filters" style="width: -webkit-fill-available; display: flex; align-items: center;">
                    <div class="col-md-3">
                        <input name="branch_id" v-model="activeBranch.id" hidden />
                        <multiselect v-model="activeBranch" @input="branchSelected()" deselect-label="Can't remove this value"
                            track-by="name" label="name" placeholder="Select Branch" :options="branches"
                            :searchable="false" :allow-empty="false" />
                    </div>
                    <div class="col-md-9">
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control" placeholder="Search Product" name="search_input"
                                v-on:keyup.13="setFilters()" v-model="searchinput">
                            <span class="input-group-btn" style="width: 15px;">
                                <apan class="btn btn-info btn-lg" @click="setFilters()" style="margin:0;">
                                    <i class="voyager-search"></i>
                                </apan>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span class="btn btn-primary" @click="clearFilters()">Clear filters
                        </span>
                    </div>
                </form> --}}
                <div class="col-md-2" style="display: flex; flex-direction: row; width: 100%;">
                    <div class="col-md-3" style="margin-top: 20px;">
                        <input name="branch_id" v-model="activeBranch.id" hidden/>
                        <multiselect
                            v-model="activeBranch"
                            @input="branchSelected()"
                            deselect-label="Can't remove this value"
                            track-by="name"
                            label="name"
                            placeholder="Select Branch"
                            :options="branches"
                            :searchable="false"
                            :allow-empty="false"
                        />
                    </div>
                    <div>
                        <small class="rr">Quantity</small>
                        <input
                            class="form-control"
                            value="1"
                            type="text"
                            min="0"
                            style="margin: 0 0 6px 0"
                        >
                    </div>
                </div>
            </div>
            <div class="paginator_ containers_">
                <table style="width: 100%;">
                    <thead>
                        <tr />
                        <th v-if="!activeBranch.id">Branch</th>
                        <th>Product name</th>
                        <th>Stocks</th>
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
                                <td colspan="2">No record</td>
                            </tr>
                        {{-- @endforelse --}}
                    </tbody>
                </table>
                <br><br>
                {{-- {{ $branch_products ?? ''->links() }} --}}
            </div>
        </section>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory.css') }}">

    <script>
        var app = new Vue({
        el: '#app',
        components: {
            Multiselect: window.VueMultiselect.default,
        },
        data () {
            return {
                searchinput: '',
                activeBranch: [],
                branches: {!! $branches ?? '' !!},
            }
        }
    })
    </script>
@endsection
