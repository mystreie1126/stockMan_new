console.log('running');

//dirty code...
$('.get_sales_form').click((e,i)=>{
  console.log(22);
    $('.rep_type_form').each((i,e)=>{
      if(!$(e).hasClass('hide')){
        $(e).addClass('hide');
      }
    });
    $('#sales_rep_form').removeClass('hide');

});

$('.get_order_form').click((e,i)=>{
  $('.rep_type_form').each((i,e)=>{
    if(!$(e).hasClass('hide')){
      $(e).addClass('hide');
    }
  });
  $('#order_rep_form').removeClass('hide');
});

$('.get_custom_form').click((e,i)=>{
  $('.rep_type_form').each((i,e)=>{
    if(!$(e).hasClass('hide')){
      $(e).addClass('hide');
    }
  });
  $('#custom_rep_form').removeClass('hide');
});


//trying to be clean now i promise

$('#rep_get_sales').click((e)=>{

    let url = api+'/get_rep_sales_form';
    e.preventDefault();
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      url:url,
      type:'post',
      dataType:'json',
      data:{
        shop_id:$('#rep_shop_name').val(),
        date_start:$('.rep_start_date').val(),
        date_end:$('.rep_end_date').val(),
        time_start:$('.rep_start_time').val(),
        time_end:$('.rep_end_time').val()
      },
      success:function(response){
        console.log(response);

        let html = '';
        let sold_details = response.store_sale.concat(response.web_sale);
        sold_details.forEach(function(element) { element.Modified = "No";});

        console.log(sold_details);

        sold_details.forEach((e)=>{
          html+='<tr>'+
                  '<td>'+e.product_reference+'</td>'+
                  '<td>'+e.product_name+'</td>'+
                  '<td>'+e.quantity+'</td>'+
                  '<td><input type="number" value='+e.quantity+' class="validate rep_send_qty indigo-text" disabled></td>'+
                  '<td class="orange-text">'+e.hq_stock+'</td>'+
                  '<td class="rep_sales_modified">No</td>'+
                  '<td>'+response.shop_name+'</td>'+
                  '<td class="rep_edit_btns">'+
                    '<a href="javascript:void(0)" class="rep_update_qty indigo-text" style="font-weight:bolder"><i class="material-icons">edit</i></a>'+
                    '<a href="javascript:void(0)" class="rep_confirm_update green-text"><i class="material-icons">done_all</i></a>'+
                    '<a href="javascript:void(0)" class="rep_remove_product red-text"><i class="material-icons">delete</i></a>'+
                  '</td>'+
               '</tr>'
        });

        $('.rep_sale_form_details').html(html);
        $('.rep_sale_table_msg').html('<p class="flow-text">'+response.shop_name+' has total '+sold_details.length+' type of product</p>')

        $('.rep_sale_table').on('click','.rep_update_qty',function(){
          $(this).parent().parent().find('.rep_send_qty').removeAttr('disabled');
        });

        $('.rep_sale_table').on('click','.rep_remove_product',function(){
          $(this).parent().parent().remove();
        });

        $('.rep_sale_table').on('click','.rep_confirm_update',function(){
            if(!$(this).parent().parent().find('.rep_send_qty').attr('disabled')){
            $(this).parent().parent().find('.rep_send_qty').attr('disabled','diabled');
          }
        });

      }
    });
});
