@extends('voyager::master')
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <div id="dashboard" class="clearfix container-fluid row">
            <div>
                <form method="get" class="form-search">
                    <div style="display: flex">
                    </div>
                </form>
            </div>

            <section>
                <div class="paginator_ containers_">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Remaining Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($low_stocks ?? [] as $key => $value)
                                <tr style="border-top: solid #5c5c5c29 1px" @click="rankingClicked({{ $value }})">
                                    <td>
                                        {{ $value->product->name }}
                                    </td>
                                    <td>
                                        {{ $value->quantity }}
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
                    {{ $low_stocks->links() }}
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
    {{-- <script src="https://unpkg.com/vue-multiselect@2.1.0"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"
        integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/predictions.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-switch.css') }}">
    <script>
        var vm = new Vue({
            el: '#dashboard',
            components: {
                // Multiselect: window.VueMultiselect.default,
            },
            data: {
                value: [],
                order_by: [],
            },
            created() {
                this.$nextTick(() => {})
                // this.getCharts()
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
