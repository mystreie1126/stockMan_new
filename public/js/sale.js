$(document).ready(function(){

	//recent order call
	let recent_order_url = api+'/recent_orders';



  	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  	$.ajax({
  		url:recent_order_url,
  		type:'get',
  		dataType:'json',
  		success:function(response){
  			console.log(response);

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





















});
