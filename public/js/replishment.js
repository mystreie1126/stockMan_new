console.log('running');
var send_arr = [],shop_name;

var update_send_action = function(res){

  let html = '';
  res.forEach((e)=>{
    html += '<tr>'+
              '<td class="center">'+e.shop_name+'</td>'+
              '<td class="center">'+e.sendQty+'</td>'+
              '<td class="center">'+e.last_update+'</td>'+
              '<td class="center">'+
                '<button class="btn green waves-effect waves-light send_action_btn ready_to_send">Send</button>'+
                '<input type="hidden" value='+e.shop_id+'>'+
                '<button class="btn indigo waves-effect waves-light send_action_btn ready_to_export">Export</button>'+
                '<button class="btn red waves-effect waves-light send_action_btn ready_to_delete">Delete</button>'+
              '</td>'+
            '</tr>'
      });
    $('.rep_saved_list_table_details').html(html);
}




$(document).ready(function(){

// if ( $('.rep_saved_list_table_details').children().length == 0 ) {
//     $('.rep_saved_list_table').addClass('hide');
//     $('#rep_saved_list').append('<p class="flow-text">No previous saved list</p>');
// }

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.ajax({
      type:'get',
      dataType:'json',
      url:api+'/getsavedlist',
      success:function(response){
          update_send_action(response);
          $('.loading-effect').addClass('hide');
          if(response.length == 0){
            $('.rep_saved_list_table').addClass('hide');
          }
      },
      error:function(){
        $('.loading-effect').addClass('hide');
      }
    });

    //dirty code...

    $('.rep_edit_btns_each').click((e)=>{
      Materialize.toast('<p class="green-text">saved</p>', 2000);

    });
    $('.get_sales_form').click((e,i)=>{

        $('.rep_type_form').each((i,e)=>{
          if(!$(e).hasClass('hide')){
            $(e).addClass('hide');
          }
        });
        $('#sales_rep_form').removeClass('hide');

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

      $('.loading-effect').removeClass('hide');

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
            response.web_sale.forEach((e)=>{
              e.id_shop = response.shop_id
            });
            console.log(response.web_sale);

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

            /* missing block*/

            let missing_html = '';
            response.missing.forEach((e)=>{
              missing_html+='<tr>'+
                              '<td>'+e.product_name+'</td>'+
                              '<td>'+e.product_reference+'</td>'+
                              '<td>'+e.quantity+'</td>'+
                            '</tr>'
            });
            $('.missing_products_details').html(missing_html);
            $('.missing_shop').text(response.shop_name);

            $('.export-missing').click((e)=>{
                $('.missing_products').csvExport({
                  title:response.shop_name+' missing products'
                });
            })



              /* end of missing block*/


            $('.rep_sale_form_details').html(html);

            //loading finished
            $('.loading-effect').addClass('hide');

            $('.rep_sale_table').removeClass('hide');
            $('.rep-sale_form_btn').html(
              '<a class="waves-effect waves-light btn teal lighten-2 darken-4" id="export_sale_list" style="margin-right:10px;" ><i class="material-icons right">eject</i>Export</a>'+
              '<a class="waves-effect waves-light btn  indigo darken-4 save_sale_to_list"><i class="material-icons right">save</i>Save</a>'
            );
            $('.rep_sale_table_msg').html(
                '<p class="flow-text">Total <span class="total_product_msg indigo-text">'+sold_details.length+'</span> type of product(s) in the list</p>'+
                '<input type="hidden" value='+response.date[0]+'>'+
                '<input type="hidden" value='+response.date[1]+'>'+
                '<input type="hidden" value='+response.shop_name+'>'
            );

            $('.rep_sale_table').on('click','.rep_update_qty',function(){
              $(this).parent().parent().find('.rep_send_qty').removeAttr('disabled');
            });

            $('.rep_sale_table').on('click','.rep_remove_product',function(){

              let ele = $(this).parent().parent();

              $.confirm({
                   title: '<span class="red-text">Warning</span>',
                   content: 'Do you wanna remove this item from the list?',
                   buttons: {
                       ok: {
                           text: "Yes",
                           btnClass: 'btn orange',
                           action: function(){
                              ele.remove();
                              $('.total_product_msg').text($('.rep_sale_form_details tr').length);
                           }
                       },
                       cancel: function(){}
                   }
                  });
            });
            $('.rep_sale_table').on('click','.rep_confirm_update',function(){
                if(!$(this).parent().parent().find('.rep_send_qty').attr('disabled')){
                $(this).parent().parent().find('.rep_send_qty').attr('disabled','disabled');
                Materialize.toast('Quantity updated Successfully', 2000)
                $(this).parent().parent().find('.rep_sales_modified').text('Yes');
              }
            });
          }
        });
    });


    /*====================================save list actions ======================================================*/

    //1.save to list
    $('.rep-sale_form_btn').on('click','.save_sale_to_list',function(e){
        $('.save_sale_to_list').attr('disabled','disabled');
        $('.save_sale_to_list').text('loading...');
        //loading...
        $('.loading-effect').removeClass('hide');

        $('.rep_sale_form_details tr').each(function(a,b){

          let ref = $('.final_ref',b).text(),
              name = $('.final_name',b).text(),
              qty = $('.rep_send_qty',b).val(),
              shop_product_id = $('.id_in_shop',b).val(),
              pos_product_id = $('.id_in_pos',b).val(),
              shop_id = $('.final_shop_id',b).val();

          send_arr.push({ref:ref,name:name,qty:qty,shop_product_id:shop_product_id,pos_product_id:pos_product_id,shop_id:shop_id});
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
              $('#export_sale_list').remove();

              send_arr = [];
              console.log(response);
              Materialize.toast('<p class="green-text">List Saved Successfully</p>', 2000);
              $('.rep_sale_table').addClass('hide');
              $('.rep_sale_table_msg').empty();

              update_send_action(response);
              $('.loading-effect').addClass('hide');
              $('.rep_saved_list_table').removeClass('hide');


            },
            error:function(){
              $('.save_sale_to_list').attr('disabled','disabled');
              $('.save_sale_to_list').text('error');
              $('.rep_sale_table').addClass('hide');
              $('.rep_sale_table_msg').empty();
              $('.loading-effect').addClass('hide');


            }
          })
        }
    });


    //2.export saved list

      $('.rep-sale_form_btn').on('click','#export_sale_list',function(){
          $('.rep_sale_table').csvExport({
            title:$('.rep_sale_table_msg input')[2].defaultValue+' sale list from' + $('.rep_sale_table_msg input')[0].defaultValue + '_to_'+$('.rep_sale_table_msg input')[1].defaultValue
          });
      });


    /*====================================save list end ======================================================*/

    //ready to send

    $('.rep_saved_list_table_details').on('click','.ready_to_send',function(){
      //loading ..
      $('.loading-effect').removeClass('hide');

      let shop_id = $(this).siblings('input').val(),
              row = $(this).parent().parent();
      $.confirm({
           title: '<span class="red-text">Warning</span>',
           content: 'Do you wanna to proceed?',
           buttons: {
               ok: {
                   text: "Yes",
                   btnClass: 'btn orange',
                   action: function(){
                     $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                     $.ajax({
                       type:'post',
                       dataType:'json',
                       url:api+'/ready_to_send',
                       data:{
                         shop_id:shop_id
                       },
                       success:function(response){
                         console.log(response);

                         $('.loading-effect').addClass('hide');
                         Materialize.toast('<p class="green-text bolder">List Successfully updated!</p>', 2000);
                       }
                     });
                      row.remove();
                   }
               },
               cancel: function(){}
           }
          });
    });

    //ready to export
    $('.rep_saved_list_table_details').on('click','.ready_to_export',function(){
      //loading ..
      $('.loading-effect').removeClass('hide');

      let shop_id = $(this).siblings('input').val(),
              row = $(this).parent().parent();
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        type:'post',
        dataType:'json',
        url:api+'/ready_to_export',
        data:{
          shop_id:shop_id
        },
        success:function(response){
           console.log(response)
           let html = '';

           response.list.forEach((e)=>{
              html += '<tr>'+
                        '<td>'+e.name+'</td>'+
                        '<td>'+e.reference+'</td>'+
                        '<td>'+e.Quantity+'</td>'+
                      '</tr>'
           });

           $('.export-table_details').html(html);

           $('.loading-effect').addClass('hide');

           $('.export_table').csvExport({
            title:'Send to '+response.shop+' on '+response.date
           })
        },
      });

    });

    //ready to delete
    $('.rep_saved_list_table_details').on('click','.ready_to_delete',function(){

      //loading ..
      $('.loading-effect').removeClass('hide');

      let shop_id = $(this).siblings('input').val(),
              row = $(this).parent().parent();
      $.confirm({
           title: '<span class="red-text">Warning</span>',
           content: 'Do you wanna to delete this list?',
           buttons: {
               ok: {
                   text: "Yes",
                   btnClass: 'btn red',
                   action: function(){
                     $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                     $.ajax({
                       type:'post',
                       dataType:'json',
                       url:api+'/ready_to_delete',
                       data:{
                         shop_id:shop_id
                       },
                       success:function(response){
                         console.log(response);
                         row.remove();
                         //loading finished..
                         $('.loading-effect').addClass('hide');

                         Materialize.toast('<p class="red-text">List Successfully Deleted</p>', 2000);

                       }
                     })

                   }
               },
               cancel: function(){}
           }
          });
    });



/*==========================================Additonal items==============================================*/




/*==========================================end of Additonal ==============================================*/

});//end of everything
