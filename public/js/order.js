$(document).ready(function(){

var text_init = function(e){
  e.attr('disabled','disabled');
  e.text('loading...');
}

var response_init = function(){
  $('.response_order_details').remove();
  $('.update_stock_by_order').remove();
  $('.customer_response_details').remove();
}

$('#searchOrderInfoByRef').click(function(e){
  e.preventDefault();

  //init
  text_init($('#searchOrderInfoByRef'));
  response_init();

/* get order details */
  let ref = $('#order-ref-input').val();
      url = api+'/searchOrder/'+ref;

      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

      $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success:function(response){
          console.log(response);

          $('#searchOrderInfoByRef').removeAttr('disabled');
          $('#searchOrderInfoByRef').text('Search');


          //success response
          let condition =  (Number(response.order.current_state) == 5);
          let state;

           condition  ? state = '<span class="right green-text">Delivered</span>' : state = '<span class="right red-text">Not Delivered</span>';

          let orderhtml = '<ul class="collection with-header response_order_details">'+
          			'<li class="collection-header"><h5>'+response.order.reference+state+'</h5></li>'+
          			'<li class="collection-item"><div>Created at <span class="order_date_col right indigo-text">'+response.order.date_add+'</span></div></li>'+
          			'<li class="collection-item">'+
          					'<span class="order_amount_col indigo-text">'+Number(response.order.total_paid_tax_incl)+'</span> &euro; Tax.inc Total'+
                    // '<input type="hidden" value='+response.order.id_order+'id="response_id_order">'+
                    // '<input type="hidden" value='+response.branch+'id="response_id_shop">'+
          					'<a class="view-details-order_col orange-text modal-trigger right" href="#modal1">View Order Details</a>'+
          			'</li>'+
          		'</ul>';

          $('.order_form_col').append(orderhtml);

          let order_details_tableData = '';

          response.order_details.forEach((e,i)=>{
              order_details_tableData += '<tr>'+
                                            '<td>'+e.product_reference+'</td>'+
                                            '<td>'+e.product_name+'</td>'+
                                            '<td>'+e.qty+'</td>'+
                                            '<td>'+(e.hq_qty - e.qty)+'</td>'+
                                         '</tr>'
          });
          $('.order_details_modal').html(order_details_tableData);

          $('.orderCSV').click(()=>{
            $('.order_details_table_modal').csvExport({
              title:response.order.reference+'_'+response.customer.firstname+response.customer.lastname+''
            });
          })

          let dataChart = [];

          for(let i = 0; i < response.chartAmount.length; i++){
            dataChart.push({x:new Date(response.chartDate[i]),y:Number(response.chartAmount[i])})
          }


let chart = new CanvasJS.Chart("customer_order_history_chart", {
	animationEnabled: true,
	title:{
		text: "Customer Order History"
	},
	axisX:{
		valueFormatString: "DD MMM"
	},
	axisY: {
		title: "Order Amount euro",

	},
	data: [{
		type: "line",
		xValueFormatString: "DD MMM YYYY",
		color: "#F08080",
		dataPoints: dataChart
	}]
});

chart.render();

/* get customer infomation */

let customer_html = '<ul class="collection with-header customer_response_details">'+
     '<li class="collection-header"><h5>Customer Details</h5></li>'+
     '<li class="collection-item"><div>Name:<span class="right indigo-text">'+response.customer.firstname+' '+response.customer.lastname+'</span></div></li>'+
     '<li class="collection-item">Mobile:<span class="right indigo-text">'+response.mobile+'</span></div></li>'+
    '<li class="collection-item">Partner:<span class="right indigo-text">'+response.isPartner+'</span></div></li>'+
    '<li class="collection-item">Owned Branch:<span class="right indigo-text">'+response.branch+'</span></div></li>'+
    '<li class="collection-item"><div>Total Valid Order Placed:<span class="right indigo-text">'+response.$total_valid_order+'</span></div></div></li>'+
     '<li class="collection-item"><div>Total Order Amount:<span class="right indigo-text">'+response.total_order_amount+' &euro;</span></div></div></li>'+
     '<li class="collection-item"><div>Order since:<span class="right indigo-text">'+response.orderSince+'</span></div></div></li>'+
     '<li class="collection-item"><div>Last Order Created at:<span class="right indigo-text">'+response.lastOrder+'</span></div></div></li>'+
   '</ul>'

$('.customer_form_col').html(customer_html);


      },//end of success
    error:function(){
      $('#searchOrderInfoByRef').removeAttr('disabled');
      $('#searchOrderInfoByRef').text('Search');
    }
  })
});





});
