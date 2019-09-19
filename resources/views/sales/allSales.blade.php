@extends('template')
@section('content')

<div class=" row">
	<div class="branches_general_sales">
		<div class="row" style="margin-top:10px">
			<div class="input-field col s4">
				<select class="teal-text" style="display:block" v-model="selectedDays" @change="onChange()" id="select_days">
					<option value="0">From Today</option>
					<option value="1">From Yesterday</option>
					<option value="7">From Last 7 Days</option>
					<option value="14">From Last 2 Weeks</option>
					<option value="30">From Last Month</option>
				</select>

				<label>Select Date</label>
			</div>
		</div>
		
	</div>
	<div class="col s12">
		<canvas id="chart"></canvas>
	</div>
</div>
@push('sale_js')
<script type="text/javascript">

	var color = {
	    retail:'#34aeeb',
	    wholesale:'#eb5634',
	    profit:'#46eb34'
	};

	var ctx  = document.getElementById('chart').getContext('2d');
	var chart = new Chart(ctx,{
	    type: 'bar',
	    data: {
	      labels: [],
	      datasets: [
	        {
	          label: "General Product Sales (tax excl.)",
	          backgroundColor: color.retail,
	          data: []
	        },
	        {
	          label:'General Product (tax excl.)',
	          backgroundColor: color.wholesale,
	          data:[]
	        },
	        {
	          label:'Net Profit',
	          backgroundColor:color.profit,
	          data:[]
	        }
	      ]
	    },
	    options: {
	      legend: { display: true },
	      title: {
	        display: true,
	        text: 'Rockpos today general products sale'
	      }
	    }
	});


	var allBranchSalesChart = new Vue({
	    el:'.branches_general_sales',
	    data:{
	        selectedDays:''
	    },
	    methods:{
	        onChange:function(){

	            console.log(this.selectedDays)
	            this.getChartByDate(this.selectedDays);

	        },
	        getChartByDate:function(date){
	            axios({
	                method:'post',
	                url:stockMan+'all_shop_sales_charts',
	                data:{
	                    date:date
	                }
	            }).then((e)=>{
	                console.log(e.data.shop_data);
	                var shopData = e.data.shop_data;
	                 chart.data.labels = shopData.map((e)=>e.name);
	                 chart.data.datasets[0].data = shopData.map((e)=>e.retail);
	                 chart.data.datasets[1].data = shopData.map((e)=>e.wholesale);
	                 chart.data.datasets[2].data = shopData.map((e)=>e.profit);
	                 chart.options.title.text ='Rockpos products sales from ' + $( "#select_days option:selected" ).text();
	                 chart.update();

	            })
	        }
	    },
	    created:function(){
	       this.getChartByDate(0);
	    }
	})
</script>
@endpush

@stop
