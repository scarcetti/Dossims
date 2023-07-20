@extends('voyager::master')
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <span class="branches_content" hidden>
            {!! json_encode($branches) ?? '' !!}
        </span>
        <div id="dashboard" class="clearfix container-fluid row">
            <div>
                {{-- {{$top_products}} --}}
                {{$branches}}
                @{{branch}}
                <form method="get" class="form-search">
                    <div style="display: flex">
                        <div style="margin: 22px">
                            <label>FIlter products</label>
                            <input type="text" name="filter_value" :value="value" hidden>
                            <multiselect v-model="value" :options="options" :searchable="false"
                                @input="submitFilter()" :close-on-select="true" :show-labels="false"></multiselect>
                        </div>
                        <div style="margin: 22px">
                            <label>Order by</label>
                            <input type="text" name="order_by" :value="order_by" hidden>
                            <multiselect v-model="order_by"
                                :options="['Most selling', 'Most profitable', 'Least selling', 'Least profitable']"
                                :searchable="false" @input="submitFilter()" :close-on-select="true"
                                :show-labels="false"></multiselect>
                        </div>-
                        <div style="margin: 22px">
                            <label>Filter branch</label>
                            <input type="text" name="branch" :value="branch" hidden>
                            <multiselect
                                v-model="branch"
                                track-by="name"
                                label="name"
                                placeholder="Select Branch"
                                :options="branches"
                                :searchable="false"
                                :close-on-select="true"
                                style="min-width: 20vw;"
                            />
                        </div>-
                        <div style="margin: 22px; display: flex; flex-direction: column;">
                            <label>Hide tables</label>
                            <label class="switch">
                                @if (isset(Request::all()['hide_tables']))
                                    <input name="hide_tables" type="checkbox" checked onclick="submit()">
                                @else
                                    <input name="hide_tables" type="checkbox" onclick="submit()">
                                @endif
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <section>
                <div class="paginator_ containers_">
                    <table style="width: 100%;">
                        <thead>
                            <tr />
                            <th>Ranking</th>
                            @if (isset(Request::all()['hide_tables']))
                                <th>Product</th>
                            @else
                                <th>Predictions</th>
                            @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_products as $key => $value)
                                @if (isset(Request::all()['hide_tables']))
                                    <tr style="border-top: solid #5c5c5c29 1px"
                                        @click="rankingClicked({{ $value }})">
                                        <td>
                                            <p>#&nbsp;{{ $key + 1 }}</p>
                                        </td>
                                        <td>
                                            {{ $value->branchProduct->product->name }}
                                        </td>
                                    </tr>
                                @else
                                    <tr style="border-top: solid #5c5c5c29 1px"
                                        @click="rankingClicked({{ $value }})">
                                        <td>
                                            <h3>#&nbsp;{{ $key + 1 }}</h3>
                                        </td>
                                    </tr>
                                    <tr class="graph-{{ $value->branch_product_id }}"
                                        branch_product_id={{ $value->branch_product_id }}>
                                        <td colspan="2">
                                            <div class="col-lg-11 col-md-11">
                                                <div class="chart-container-width-basis"></div>
                                                <figure class="chart-container">
                                                    <div id="chart-{{ $value->branch_product_id }}" class="chart"></div>
                                                </figure>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="2">No record</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br><br>
                    {{-- {{ $branch_products->links() }} --}}
                </div>
                <center>
                    <a class="btn btn-dark" href="{{ ENV('APP_URL') }}/admin/" readonly>Return to Dashboard</a>
                </center>
            </section>
        </div>

    </body>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.2/echarts.min.js"
        integrity="sha512-VdqgeoWrVJcsDXFlQEKqE5MyhaIgB9yXUVaiUa8DR2J4Lr1uWcFm+ZH/YnzV5WqgKf4GPyHQ64vVLgzqGIchyw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
        integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/predictions.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-switch.css') }}">
    <script>
        var vm = new Vue({
            el: '#dashboard',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data: {
                value: ['{{ Request::all()['filter_value'] ?? 'Weekly' }}'],
                options: ['Weekly', 'Monthly', 'Yearly', 'All-time'],
                selected: null,
                branches: {!! json_encode($branches) ?? '' !!},
                option: {
                    vueChart: "",
                    vueChartOption: null,
                },
                branch: ['{{ Request::all()['branch'] ?? 3 }}'],
                order_by: ['{{ Request::all()['order_by'] ?? 'Most selling' }}'],
            },
            created() {
                this.$nextTick(() => {})
                this.getCharts()
            },
            methods: {
                reroute(x) {
                    location.href = x
                },
                submitFilter() {
                    setTimeout(() => {
                        $('form.form-search')[0].submit()
                    }, 50);
                },
                rankingClicked(value) {
                    this.selected = value
                    this.fetchPredictionData(value.branch_product_id)
                },
                getCharts() {
                    const list = document.querySelectorAll('[class^="graph"]')
                    // const branches = document.querySelector('span.branches_content').innerHTML
                    // const pattern = /^\s*$/g;

                    // if (!pattern.test(branches)) {
                    //     this.branches = JSON.parse(branches)
                    // }

                    setTimeout(() => {
                        list.forEach(element => {
                            console.log('element', element.attributes.branch_product_id.value)
                            this.fetchPredictionData(element.attributes.branch_product_id.value)
                        });
                    }, 100)
                },
                fetchPredictionData(branch_product_id) {
                    axios.get(`${window.location.origin}/admin/analytics/chart/${branch_product_id}`)
                    .then(response => {
                        this.option.vueChartOption = response.data
                        this.initChart(branch_product_id)
                        })
                },
                initChart(branch_product_id) {

                    this.option.vueChart = echarts.init(document.getElementById(`chart-${branch_product_id}`),
                    null, {
                        renderer: 'svg'
                    })
                    this.option.vueChart.setOption(this.option.vueChartOption)

                },
            }
        })
    </script>
@endsection
