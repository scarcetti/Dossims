@extends('voyager::master')
@section('content')
    <style>
        /* tr.show {
            display: unset;
        }
        tr.[class^="graph-"] {
            display: none !important;
            border: solid red 2px;
        } */
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <div id="dashboard" class="clearfix container-fluid row">
            <div>
                <form method="get" class="form-search">
                    <div>
                        <div style="margin: 22px">
                            <label>FIlter products</label>
                            <input type="text" name="filter_value" :value="value" hidden>
                            <multiselect v-model="value" :options="options" :searchable="false"
                                @input="submitFilter()" :close-on-select="true" :show-labels="false"></multiselect>
                        </div>
                    </div>
                </form>
            </div>
            {{-- @{{ selected }} --}}
            {{-- <div class="col-lg-12 col-md-12">
                <div class="chart-container-width-basis"></div>
                <figure class="chart-container">
                    <div id="chart1" class="chart"></div>
                </figure>
            </div> --}}
            <section>
                <div class="paginator_ containers_">
                    <table style="width: 100%;">
                        <thead>
                            <tr />
                            <th>Ranking</th>
                            <th>Predictions</th>
                            {{-- <th>Sales</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_products as $key => $value)
                                <tr style="border-top: solid #5c5c5c29 1px" @click="rankingClicked({{ $value }})">
                                    <td><h3>#&nbsp;{{ $key + 1 }}</h3></td>
                                    {{-- <td>{{ $value->branchProduct->product->name }}</td> --}}
                                    {{-- <td>{{ $value->count_ }}</td> --}}
                                </tr>
                                <tr class="graph-{{ $value->branch_product_id }}" branch_product_id={{ $value->branch_product_id }}>
                                    <td colspan="2">
                                        <div class="col-lg-11 col-md-11">
                                            <div class="chart-container-width-basis"></div>
                                            <figure class="chart-container">
                                                <div id="chart-{{$value->branch_product_id}}" class="chart"></div>
                                            </figure>
                                        </div>
                                    </td>
                                </tr>
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
                option: {
                    vueChart: "",
                    vueChartOption: null,
                },
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

                    // setTimeout(() => {
                    //     $(`#prediction_dialog`).modal({
                    //         backdrop: 'static',
                    //         keyboard: false
                    //     });
                    // }, 50)
                },
                getCharts() {
                    const list = document.querySelectorAll('[class^="graph"]')

                    setTimeout(() => {
                        list.forEach(element => {
                            console.log('element', element.attributes.branch_product_id.value)
                            // this.initChart(element.attributes.branch_product_id.value)
                            this.fetchPredictionData(element.attributes.branch_product_id.value)
                        });
                    },100)
                },
                fetchPredictionData(branch_product_id) {
                    // const a = $(`.graph-${branch_product_id}`)[0]
                    // const a = document.querySelector(`.graph-${branch_product_id}`).classList.toggle('hide');
                    // console.log(1123, a)
                    axios.get(`${window.location.origin}/admin/analytics/chart/${branch_product_id}`)
                        .then(response => {
                            // alert('Quotation created!')
                            // window.location.reload()
                            // console.log(123, response.data)
                            this.option.vueChartOption = response.data
                            this.initChart(branch_product_id)
                        })
                },
                initChart(branch_product_id) {

                        this.option.vueChart = echarts.init(document.getElementById(`chart-${branch_product_id}`), null, {
                            renderer: 'svg'
                        })
                        this.option.vueChart.setOption(this.option.vueChartOption)
                },
            }
        })
    </script>
@endsection
