@extends('voyager::master')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>
  	<div id="dashboard" class="clearfix container-fluid row">
    	<div class="col-lg-12 col-md-12">
      		<div class="chart-container-width-basis"></div>
		    <figure class="chart-container">
		    	{{-- <span>Containers Received in the past 12 months</span> --}}
		    	<div id="chart1" class="chart"></div>
		    </figure>
	    </div>
        <div class="col-lg-12 col-md-12">
            <div class="chart-container-width-basis"></div>
          <figure class="chart-container">
              {{-- <span>Containers Received in the past 12 months</span> --}}
              <div id="chart2" class="chart"></div>
          </figure>
      </div>
  	</div>
    <center>
        <a class="btn btn-dark" href="{{ ENV('APP_URL') }}/admin/" readonly>Return to Dashboard</a>
    </center>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.2/echarts.min.js" integrity="sha512-VdqgeoWrVJcsDXFlQEKqE5MyhaIgB9yXUVaiUa8DR2J4Lr1uWcFm+ZH/YnzV5WqgKf4GPyHQ64vVLgzqGIchyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/predictions.css') }}">
<script>
  var vm = new Vue({
    el: '#dashboard',
    data:{
      option: {
      	vueChart: "",
      	vueChartOption: {!! $toril ?? '{}' !!},
      },
      option2: {
      	vueChart: "",
      	vueChartOption: {!! $tagum ?? '{}' !!},
      },
    },
    created() {
      this.$nextTick(() => {
        this.initChart1()
        this.initChart2()
      })
    },
    methods: {
      initChart1() {
        this.option.vueChart = echarts.init(document.getElementById('chart1'), null, {renderer: 'svg'})
        this.option.vueChart.setOption(this.option.vueChartOption)
      },
      initChart2() {
        this.option2.vueChart = echarts.init(document.getElementById('chart2'), null, {renderer: 'svg'})
        this.option2.vueChart.setOption(this.option2.vueChartOption)
      },
      reroute(x) {
        location.href = x
      },
    }
  })
</script>
@stop
