console.log('running');
var send_arr = [],shop_name;
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

/* getting sales replishment list */
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
        sold_details.forEach((e)=>{
          html+='<tr>'+
                  '<td class="final_ref">'+e.product_reference+'</td>'+
                  '<td class="final_name">'+e.product_name+'</td>'+
                  '<td>'+e.quantity+'</td>'+
                  '<td><input type="number" value='+e.quantity+' class="validate rep_send_qty indigo-text" disabled></td>'+
                  '<td class="orange-text">'+e.hq_stock+'</td>'+
                  '<td class="rep_sales_modified">No</td>'+
                  '<td class="rep_sales_shop_name">'+response.shop_name+'</td>'+
                  '<td class="rep_edit_btns">'+
                    '<a href="javascript:void(0)" class="rep_update_qty indigo-text" style="font-weight:bolder"><i class="material-icons">edit</i></a>'+
                    '<a href="javascript:void(0)" class="rep_confirm_update green-text"><i class="material-icons">done_all</i></a>'+
                    '<a href="javascript:void(0)" class="rep_remove_product red-text"><i class="material-icons">delete</i></a>'+
                    '<input type="hidden" class="id_in_shop" value='+e.shop_product_id+'>'+
                    '<input type="hidden" class="id_in_pos" value='+e.pos_product_id+'>'+
                    '<input type="hidden" class="final_shop_id" value='+e.id_shop+'>'+
                  '</td>'+

               '</tr>'
        });

        $('.rep_sale_form_details').html(html);
        $('.rep-sale_form_btn').html('<a class="waves-effect waves-light btn right  indigo darken-4 save_sale_to_list"><i class="material-icons right">save</i>Save</a>');
        $('.rep_sale_table_msg').html('<p class="flow-text">'+response.shop_name+' has total '+sold_details.length+' type of product</p>')
        $('.rep_sale_table').on('click','.rep_update_qty',function(){
          $(this).parent().parent().find('.rep_send_qty').removeAttr('disabled');
        });
        $('.rep_sale_table').on('click','.rep_remove_product',function(){
          let ele =  $(this).parent().parent().find('td');
          //alert('Do you wanna remove '+ ele[0].text()+' '+ele[1].text()+ ' from list?');
          $(this).parent().parent().remove();
        });
        $('.rep_sale_table').on('click','.rep_confirm_update',function(){
            if(!$(this).parent().parent().find('.rep_send_qty').attr('disabled')){
            $(this).parent().parent().find('.rep_send_qty').attr('disabled','disabled');
          }
        });
      }
    });
});


$('.rep-sale_form_btn').on('click','.save_sale_to_list',function(e){
    $('.save_sale_to_list').attr('disabled','disabled');
    $('.save_sale_to_list').text('loading...');
    $('.rep_sale_form_details tr').each(function(a,b){
      let ref = $('.final_ref',b).text(),
          name = $('.final_name',b).text(),
          qty = $('.rep_send_qty',b).val(),
          shop_name = $('rep_sales_shop_name',b).text(),
          shop_product_id = $('.id_in_shop',b).val(),
          pos_product_id = $('.id_in_pos',b).val(),
          shop_id = $('.final_shop_id',b).val();

      send_arr.push({ref:ref,name:name,shop_name:shop_name,qty:qty,shop_product_id:shop_product_id,pos_product_id:pos_product_id,shop_id:shop_id});
    });

    if(send_arr.length > 0){
      console.log(JSON.stringify(send_arr));
      let url = api+'/save_sale_to_list';

      e.preventDefault();
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        url:url,
        type:'post',
        dataType:'json',
        data:JSON.stringify(send_arr),
        success:function(response){
          $('.rep_sale_form_details tr').remove();
          $('.save_sale_to_list').remove();
          send_arr = [];
          console.log(response);


        },
        error:function(){
          $('.save_sale_to_list').attr('disabled','disabled');
          $('.save_sale_to_list').text('error');
        }
      })
    }


});
