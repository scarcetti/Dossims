@extends('voyager::master')
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>
        <span class="branches_content" hidden>
            {!! json_encode($branches) ?? '' !!}
        </span>
        <div id="dashboard" class="clearfix container-fluid row">
            <div>
                {{-- @{{branch}} --}}
                {{-- @{{id}} --}}
                {{-- VALUE: @{{value}} || @{{value.id}} --}}
                <form method="get" class="form-search">
                    <div style="display: flex">
                        <div style="margin: 22px">
                            <label>Select Branch:</label>
                            <input name="filter_value" type="number" :value="id" hidden>
                            <multiselect
                                v-model="value"
                                track-by="name"
                                label="name"
                                placeholder="Select Branch"
                                :options="branch"
                                :searchable="false"
                                :close-on-select="true"
                                :show-labels="false"
                                :allow-empty="true"
                                style="min-width: 20vw;"
                            />
                        </div>
                    </div>
                </form>
                <button :disabled="!value.id" @click="removeFilter()" class="btn btn-sm btn-primary pull-left edit" type="submit" style="margin-top: 5px;">Remove Filter</button>
            </div>
            <div v-if="value.length === 0">
                <div v-for="(item, index) in branches" v-if="item.set" class="col-lg-12 col-md-12">
                    <div class="chart-container-width-basis"></div>
                    <figure class="chart-container">
                        <div :id="`chart-${item.branch}`" class="chart"></div>
                    </figure>
                </div>
            </div>
            <div v-else>
                {{-- VALUE: @{{value}} --}}
                <div v-for="(item, index) in branches" v-if="item.id===value.id && item.set" class="col-lg-12 col-md-12">
                    <div class="chart-container-width-basis"></div>
                    <figure class="chart-container">
                        <div :id="`chart-${item.branch}`" class="chart"></div>
                    </figure>
                </div>
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
                value: [],
                filter: [],
                id: 0
            },
            created() {
                this.getChartData()
                this.getBranch()
            },
            methods: {
                removeFilter(){
                    this.value = []
                    this.getChartData()
                },
                submitFilter(){

                },
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
                getBranch(){
                    this.branches.forEach(item => {
                        if (item.set) {
                            console.log(item)
                                const branch = {
                                    id: item.id,
                                    name: item.raw_name,
                                    set: item.set,
                                    branch: item.branch
                                }
                            this.branch.push(branch)
                        }
                    })
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
                    console.log('value:',this.value)
                    this.branches.forEach(item => {
                        if (item.set) {
                            if(item.id == this.value.id){

                                const x = echarts.init(document.getElementById(`chart-${item.branch}`), null, {
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
