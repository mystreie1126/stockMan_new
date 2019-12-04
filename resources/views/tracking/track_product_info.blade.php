@extends('template')
@section('content')
@if(Auth::check())
	<div class="row container">
		<div class="input-field col s3">
		    <select class="time">
	      		<option value="3"selected>Last 3 Days</option>
	     		<option value="7">Last Week</option>
		      	<option value="14">Last 2 Weeks</option>
		      	<option value="30">Last Month</option>
		    </select>
		    <label>Materialize Select</label>
  		</div>
		<div class="input-field col s7">
          	<input placeholder="Placeholder" id="barcode_name" type="text" class="validate">
          	<label for="first_name">Barcode or name</label>
    	</div>
    	<button class="getCharts btn col s2" style="transform: translateY(50%);">Go</button>	
		
		
		<div class="card col s12">
		    <div class="card-content">
		     	<canvas class="product_detail_chart"></canvas>
		    </div>
		</div>
		
		
		<div class="card col s12">
		    <div class="card-content">
		     	<canvas class="selling_trend_chart"></canvas>
		    </div>
		</div>
		
	</div>




@endif
@stop
@push('track_product_standard')
<script>


	var product_detail_chart = new Chart($('.product_detail_chart'),{
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
			    yAxes: [{ ticks:{beginAtZero: true} }]
			}
		}
	});


	var selling_trend_chart = new Chart($('.selling_trend_chart'),{
		type:'line',
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
			    yAxes: [{ ticks:{beginAtZero: true} }]
			}
		}
	})

	
	$('.getCharts').click(function(e){
		e.preventDefault();
		let days = $('.time :selected').val(),
			barcode_name = $('#barcode_name').val();

		$.ajax({
			type:'get',
			url:api_endpoint+`charts-product-detail?days=${days}&barcode_name=${barcode_name}`,
			success:function(res){
				console.log(res)
				if(res.status == 'success' && res.data.stock_details.length > 0){
					//draw_chart($('.selling_detail_chart'),'bar','')
					let productDetail_datasets = [
						{
							label:'Current Stock',
							backgroundColor:'#4260f5',
							data:res.data.stock_details.map(e=>e.stock)
						},
						{
							label:'Total Sold',
							backgroundColor:'#f55d42',
							data:res.data.stock_details.map(e=>e.sold)
						},
						{
							label:'Warehouse Send',
							backgroundColor:'#4bf542',
							data:res.data.stock_details.map(e=>e.send)
						},
						{
							label:'Standard Stock',
							backgroundColor:'#f2f542',
							data:res.data.stock_details.map(e=>e.standard)
						}
						
					];
					let productDetail_shops = res.data.stock_details.map(e=>e.name);

					let productDetail_text = `${res.data.product_info.name} ${res.data.product_info.reference} stock detail in last ${res.data.days_before} days`;

					product_detail_chart.data.labels = productDetail_shops;
					product_detail_chart.data.datasets = productDetail_datasets;
					product_detail_chart.options.title.text = productDetail_text;
					product_detail_chart.update();

					let selling_trends_datasets = [
						{
							label:'All shop sold',
							data:res.data.soldBetweenDays.map(e =>e[0].daily_sold),
							borderColor: "#3e95cd",
							fill:false
						}
					];

					let selling_trends_labels = res.data.soldBetweenDays.map(e=>e[0].v_date.slice(0,10));
					let selling_trends_text = `${res.data.product_info.name} ${res.data.product_info.reference} all shops selling trend in last ${res.data.days_before} days`

					 selling_trend_chart.data.labels = selling_trends_labels;
					 selling_trend_chart.data.datasets = selling_trends_datasets;
					 selling_trend_chart.options.title.text = selling_trends_text;
					 selling_trend_chart.update();

				}else{
					alert('can not find this product')
				}
			}
		})
		
	})




	// var selling_detail_chart = new Chart($('.selling_detail_chart'),{
	// 	type: 'bar',
	//     data: {
	//       	labels: ["1900", "1950", "1999", "2050"],
	//       	datasets: [
	// 	        {
	// 	          	label: "Africa",
	// 	          	backgroundColor: "#3e95cd",
	// 	          	data: [133,221,783,2478]
	// 	        }, 
	// 	        {
	// 	          	label: "Europe",
	// 	          	backgroundColor: "#8e5ea2",
	// 	         	data: [408,547,675,734]
	// 	        }
	//       	]
	//     },
	//     options: {
	// 	    title: {
	// 	        display: true,
	// 	        text: 'Population growth (millions)'
	// 	    }
	//     }
	// })
</script>
@endpush