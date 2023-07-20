@extends('voyager::master')
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <span class="branches_content" hidden>
            {!! json_encode($branches) ?? '' !!}
        </span>
        <span class="filter_branches" hidden>
            {!! json_encode($filter_branch_items) ?? '' !!}
        </span>
        <div id="dashboard" class="clearfix container-fluid row">
            @if (count($filter_branch_items) > 1)
                <div>
                    <form method="get" class="form-search">
                        <div style="display: flex">
                            <div style="margin: 22px">
                                <label>Select Branch:</label>
                                <input name="filter_value" type="text" :value="value" hidden>
                                <multiselect v-model="value" :options="filterBranches" :searchable="false"
                                    @input="submitFilter()" :close-on-select="true" placeholder="Show all"
                                    :show-labels="false"></multiselect>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <button :disabled="!value" @click="removeFilter()"
                                    class="btn btn-sm btn-primary pull-left edit" type="submit"
                                    style="margin-top: 5px;">Clear Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            <div>
                @forelse ($branches as $key => $value)
                    @if (!is_null($value['set']))
                        <div class="col-lg-12 col-md-12">
                            <div class="chart-container-width-basis"></div>
                            <figure class="chart-container">
                                <div id="chart-{{ $value['branch'] }}" class="chart"></div>
                            </figure>
                        </div>
                    @endif
                @empty
                    {{-- <div></div> --}}
                @endforelse
            </div>



        </div>

        <center>
            <a class="btn btn-dark" href="{{ ENV('APP_URL') }}/admin/" readonly>Return to Dashboard</a>
        </center>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.2/echarts.min.js"
        integrity="sha512-VdqgeoWrVJcsDXFlQEKqE5MyhaIgB9yXUVaiUa8DR2J4Lr1uWcFm+ZH/YnzV5WqgKf4GPyHQ64vVLgzqGIchyw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/predictions.css') }}">
    <script>
        var vm = new Vue({
            el: '#dashboard',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data: {
                branches: [],
                branch: [],
                filterBranches: [],
                value: ['{{ Request::all()['filter_value'] ?? 'Show all' }}'],
                filter: [],
                id: 0
            },
            created() {
                this.getChartData()
            },
            methods: {
                removeFilter() {
                    this.value = []
                    this.getChartData()
                },
                submitFilter() {
                    setTimeout(() => {
                        $('form.form-search')[0].submit()
                    }, 50);
                },
                getChartData() {
                    const branches = document.querySelector('span.branches_content').innerHTML
                    const filterBranches = document.querySelector('span.filter_branches').innerHTML
                    const pattern = /^\s*$/g;

                    if (!pattern.test(branches)) {
                        this.branches = JSON.parse(branches)

                        setTimeout(() => {
                            this.initCharts()
                        }, 100);
                    }
                    if (!pattern.test(filterBranches)) {
                        this.filterBranches = JSON.parse(filterBranches)
                    }
                },
                initCharts() {
                    this.branches.forEach(item => {
                        if (item.set) {
                            console.log(item)

                            const x = echarts.init(document.getElementById(`chart-${item.branch}`), null, {
                                renderer: 'svg'
                            })
                            x.setOption(item.set)
                        }
                    })
                },
                getSelectedBranch() {
                    this.getChartData()
                    console.log('value:', this.value)
                    this.branches.forEach(item => {
                        if (item.set) {
                            if (item.id == this.value.id) {

                                const x = echarts.init(document.getElementById(`chart-${item.branch}`),
                                    null, {
                                        renderer: 'svg'
                                    })
                                x.setOption(item.set)

                            }
                        }
                    })
                },
                reroute(x) {
                    location.href = x
                },
            },
            watch: {
                value(newValue, oldValue) {
                    this.getSelectedBranch()
                },
            }
        })
    </script>
@stop
