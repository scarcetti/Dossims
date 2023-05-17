@extends('voyager::master')
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <span class="branches_content" hidden>
            {!! json_encode($branches) ?? '' !!}
        </span>
        <div id="dashboard" class="clearfix container-fluid row">

            <div v-for="(item, index) in branches" v-if="item.set" class="col-lg-12 col-md-12">
                <div class="chart-container-width-basis"></div>
                <figure class="chart-container">
                    <div :id="`chart-${item.branch}`" class="chart"></div>
                </figure>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/predictions.css') }}">
    <script>
        var vm = new Vue({
            el: '#dashboard',
            data: {
                branches: [],
            },
            created() {
                this.getChartData()
            },
            methods: {
                getChartData() {
                    const branches = document.querySelector('span.branches_content').innerHTML
                    const pattern = /^\s*$/g;

                    if (!pattern.test(branches)) {
                        this.branches = JSON.parse(branches)

                        setTimeout(() => {
                            this.initCharts()
                        }, 100);
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
                reroute(x) {
                    location.href = x
                },
            }
        })
    </script>
@stop
