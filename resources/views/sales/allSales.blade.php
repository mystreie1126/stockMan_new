@extends('template')
@section('content')

<div class="row container">
		<div class="input-field col s12 m7 l7">
		    <select class="choose_time">
		    	<option disabled selected>Select Days before</option>
	      		<option value="1">From yesterday</option>
	      		<option value="3">Last 3 Days</option>
	     		<option value="7">Last Week</option>
		      	<option value="14">Last 2 Weeks</option>
		      	<option value="30">Last Month</option>
		    </select>
		    <label>Choose Time From</label>
  		</div>
		
		
		<div class="card col s12">
		    <div class="card-content">
		     	<canvas class="soldAmount_barChart"></canvas>
		    </div>
		</div>

		<div class="card col s12">
		    <div class="card-content">
		     	<canvas class="soldAmount_doughnutChart"></canvas>
		    </div>
		</div>
			
	</div>
@push('sale_js')
<script type="text/javascript">
	
var soldAmount_barChart = new Chart($('.soldAmount_barChart'),{
	type:'bar',
	data:{
		labels:[],
		datasets:[]
	},
	options:{
		title:{
			display:true,
			text:''
		},
		scales: {
		   yAxes:[{stacked:true}],
		   xAxes:[{stacked:true}]
		},
		tooltips:{
			callbacks:{
				label:function(tooltipItems,data){
					var label = data.datasets[tooltipItems.datasetIndex].label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += Math.round(tooltipItems.yLabel * 100) / 100;
                    
                    return label;  
				}
			}
		}
		
	}
});


var soldAmount_doughnutChart = new Chart($('.soldAmount_doughnutChart'),{
	type: 'doughnut',
    data: {
      	labels: [],
      	datasets: []
    },
    options: {
      	title: {
	        display: true,
	        text: ''
      	},
      	tooltips: {
		    callbacks: {
		      	label: function(tooltipItem, data) {
			        var dataset = data.datasets[tooltipItem.datasetIndex];
			        var meta = dataset._meta[Object.keys(dataset._meta)[0]];
			        var total = meta.total;
			        var currentValue = dataset.data[tooltipItem.index];
			        var percentage = parseFloat((currentValue/total*100).toFixed(1));
			        return currentValue + ' (' + percentage + '%)';
		     	},
		      	title: function(tooltipItem, data) {
		        	return data.labels[tooltipItem[0].index];
		      	}
		    }
		}
    }
})
	
$('.choose_time').on('change', function (e) {
    var days = this.value;
    console.log(days)
   	$.ajax({
   		type:'get',
   		url:api_endpoint + `charts-sold-amount-by-cata?days=${days}`,
   		success:function(res){
   			console.log(res)
   			
   			if(res.status == 'success'){

   				//bar chart
   				soldAmount_barChart.data.labels = res.data.allShopSoldDetails.map(e=>e[0].shopname);
   				soldAmount_barChart.data.datasets = [
   					{
   						label:'Cases',
   						backgroundColor:'#a8327d',
   						data:res.data.allShopSoldDetails.map(e=>e[0].sold_amount)
   					},
   					{
   						label:'Pre Own Phones',
   						backgroundColor:'#4832a8',
   						data:res.data.allShopSoldDetails.map(e=>e[1].sold_amount)
   					},
   					{
   						label:'Brand New Phones',
   						backgroundColor:'#32a2a8',
   						data:res.data.allShopSoldDetails.map(e=>e[2].sold_amount)
   					},
   					{
   						label:'Tempered Glasses',
   						backgroundColor:'#32a84c',
   						data:res.data.allShopSoldDetails.map(e=>e[3].sold_amount)
   					},
   					{
   						label:'Accessories (USAMS)',
   						backgroundColor:'#e0b30d',
   						data:res.data.allShopSoldDetails.map(e=>e[4].sold_amount)
   					},
   					{
   						label:'Speakers',
   						backgroundColor:'#e00d0d',
   						data:res.data.allShopSoldDetails.map(e=>e[5].sold_amount)
   					},
   					{
   						label:'IOT (XiaoMi)',
   						backgroundColor:'#4a4a48',
   						data:res.data.allShopSoldDetails.map(e=>e[6].sold_amount)
   					}
   				];
   				soldAmount_barChart.options.title.text = `All Branches selling details from last ${res.data.days_before} day(s)`
   				soldAmount_barChart.update();

   				//doughnu tChart
   				soldAmount_doughnutChart.data.labels = ["Cases", "Pre Own Phones", "Brand New Phones", "Tempered Glasses", "Accessories","Speakers","IOT"];

   				soldAmount_doughnutChart.data.datasets = [{
   					label: "Sold Amount",
			        backgroundColor: ["#a8327d", "#4832a8","#32a2a8","#32a84c","#e0b30d","#e00d0d","#4a4a48"],
			        data: res.data.categoryOverview.map(e=>e.totalAmount)
   				}]

   				soldAmount_doughnutChart.options.title.text = `All Branches different categories sold by percentage from last ${res.data.days_before} day(s)`;
   				soldAmount_doughnutChart.update();



   			}
   		}
   	})
})





</script>
@endpush

@stop
