var shop_id,export_qty;
var recent_order_url = api+'/recent_orders',
		show_sales_url = api+'/showsales';

$(document).ready(function(){
		//download top sell
		$('.select_top_qty_sold').change(function(){export_qty = $(this).val()})

		$('.export_topSale_csv').click((e)=>{
				e.preventDefault();
				
		});




		$('#shop_name').change(function(){shop_id = $(this).val()});
		$('.date_class').change(function(){
			($('.end_date').val() !='' && $('.start_date').val() != '') ? $('.get_allsales_chart_btn').prop("disabled", false):console.log('please select the date to create chart');
		});

		//right side recent orders call via page loads
  	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  	$.ajax({
  		url:recent_order_url,
  		type:'get',
  		dataType:'json',
  		success:function(response){
  			//	console.log(response);

  			let html = '';

        response.forEach((e,i)=>{

          let state = (Number(e.current_state) == 5) ? "Delivered" : "Not Delivered",
             className = (Number(e.current_state) == 5) ? "green-text" : "red-text";

          html +=
            '<li class="collection-item recent_orders_list">'+
              '<div class="tt">'+
                  '<span class="title recent_order_ref orange-text">'+e.reference+'</span>'+
                  '<span class="title recent_order_status '+className+'">'+state+'</span>'+
                '</div>'+
                    '<span class="recent_order_user">'+e.firstname+' '+e.lastname+'</span><br>'+
                '<span class="recent_order_date indigo-text">'+e.date_add+'</span>'+
                '<a class="secondary-content redirect_to_target_order"><i class="material-icons">send</i></a>'+
            '</li>'
        });

        $('#recent_order_response').html(html);

  		}//end of success

  	});//end of recent order call


		//left side sales chart call
		$('.get_allsales_chart_btn').click(function(e){
			$('.get_allsales_chart_btn').attr('disabled','disabled');
			$('.get_allsales_chart_btn').text('Creating..');
			let date_to = new Date($('.end_date').val()).toISOString().split('T')[0];
		 let	 date_from = new Date($('.start_date').val()).toISOString().split('T')[0];
		 let id_shop = Number($('#shop_name').val());

				e.preventDefault();
				$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
				$.ajax({
					url:show_sales_url,
					type:'post',
					dataType:'json',
					data:{
						date_from:date_from,
						date_to:date_to,
						id_shop:id_shop
					},
					success:function(response){
						console.log(response);
							$('.get_allsales_chart_btn').removeAttr('disabled');
							$('.get_allsales_chart_btn').text('Create Chart');
						//all shop sales chart
						let allPos_sale = [];

						response.all_pos_sale.forEach((e,i)=>{
							allPos_sale.push({y:Number(e.total),label:e.name});
						});

						console.log(allPos_sale);

						var chart1 = new CanvasJS.Chart("allshop_pos_sale_chart", {
								animationEnabled: true,
								title:{
									text: "All Shop General Sales from "+date_from+' to '+date_to
								},
								axisX:{
				          interval: 1
				        },
								axisY: {
									title: "Sales Amount"
								},
								data: [{
									type: "column",
									dataPoints: allPos_sale
								}]
							});
						chart1.render();

						//single branch last 10 week sales chart

						let lastTenWeekSale = [];
						response.each_week_sale.forEach((e,i)=>{
								lastTenWeekSale.push({x:e.week,y:Number(e.sale)});
						});
						console.log(lastTenWeekSale);
						var chart2 = new CanvasJS.Chart("shop_weekly_sale_chart", {
							animationEnabled: true,
							theme: "light2",
							title:{
								text: response.name+" Weekly POS Selling trends "
							},

							axisX: {
								title: "Week",
							},

							axisY: {
								title: "Sales Amount per Week",
							},
							data: [{
								type: "line",
								name: "Total Visit",
								markerType: "square",
								color: "#F08080",
								dataPoints: lastTenWeekSale
							}]
						});

						chart2.render();


					}//end of success
				})


		});


















});
