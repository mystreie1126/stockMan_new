
var salesList = [];







$("#download-csv").click(function(e){
    e.preventDefault();
    table.download("csv", "data.csv");
});
function sendQty_number(qty){
  return Math.floor(Number(qty));
}


//
var repList = new Vue({
  el:'#replishmentLists',
  data:{
      lists:[],
      startTime:"",
      endTime:"",
      shop_id:"",
      list_loading:false,
      submit_loading:false,
      showButton:false
  },
  created:function(){
    axios({
      method:'post',
      url:stockMan+'getSavedList',
      data:{
        rep_by_sale:1,
        custom_rep:0
      }
    }).then((res)=>{
      console.log(res.data);
    });
  },
  methods:{
    getList:function(){
      this.startTime = $('#selected_start_date').val()+" 00:00:00";
        this.endTime = $('#selected_end_date').val()+" 23:59:59";
        this.shop_id = $('#selected_shop').val();

      if(this.shop_id !== null && new Date(this.startTime).getTime() > 0 && new Date(this.endTime).getTime() > 0 ){
         console.log(`shop id is ${this.shop_id} selected from ${this.startTime} to ${this.endTime}`);
         this.list_loading = true;
         axios({
           method:'post',
           url:stockMan+'getlistbysale',
           data:{
             start_time:this.startTime,
             end_time:this.endTime,
             shop_id:this.shop_id
           }
         }).then((res)=>{
              $('.regular_list_action').removeClass('hide');
              console.log(res.data);
              this.list_loading = false;
              let all_list = res.data.sale.concat(res.data.re_instock);
              console.log(all_list);

              var table = new Tabulator('#regular_list',{
                data:all_list,
                layout:"fitColumns",
                height:"40vh",
                placeholder:"No Data Available",
                columns:[
                  {title:'Name',field:'name',width:400},
                  {title:'Barcode',field:'ref',width:100},
                  {title:'Standard',field:'standard_qty',width:100,cssClass:"red-text"},
                  {title:'Sold',field:'soldQty',width:10,visible:false},
                  {title:'Send',field:'webStock_ID',width:100,editor:"input", validator:["min:0", "max:100", "integer"]},
                ]
              })

              $('#filter-name').keyup(function(){
                table.setFilter('name','like',$('#filter-name').val())
              });

              // $('#filter-barcode').keyup(function(){
              //   table.setFilter('ref','like',$('#filter-barcode').val())
              // });


              $('.filter-action button').click((e)=>{
                e.preventDefault();
                table.hideColumn("webStock_ID")
                var myData = table.getData(true);
                    console.log(myData);

              })

      });



      }else{
        alert('Please select a correct shop or date to proceed');
      }
    },

    saveTheList:function(){
      let   valid_arr = [],
          invalid_arr = [];
      $('#test_list tr').each(function(index,ele){

          if(sendQty_number($(ele).find('.send_qty').val()) >= 0 && $(ele).find('.send_qty').val() !== ''){
              valid_arr.push({
                        send:sendQty_number($(ele).find('.send_qty').val()),
                         ref:sendQty_number($(ele).find('.l_ref').val()),
                    standard:sendQty_number($(ele).find('.l_standard').val()),
             branch_stock_id:sendQty_number($(ele).find('.l_branch_stock_id').val()),
                web_stock_id:sendQty_number($(ele).find('.l_web_stock_id').val()),
                     shop_id:repList.shop_id,
                    uploaded:0,
                 rep_by_sale:1,
                  rep_custom:0
              });
          }else{
              invalid_arr.push($(ele).find('.send_qty').val());
          }
      });
      console.log( valid_arr,invalid_arr);
      if(invalid_arr.length > 0){
        alert('send Quantity can not be empty and less than 0!');
      }else if($('#test_list').children().length > 0 && invalid_arr.length == 0){
        //sumbit here
        $('#rep_salelist_submit').attr('disabled','disabled');
        $('#rep_salelist_submit').text('loading...');
        console.log('saving');

        axios({
          method:'post',
          url:stockMan+'save_replist',
          data:JSON.stringify( valid_arr)
        }).then((res)=>{
          console.log(res.data);
          $('#test_list tr').remove();
          $('#rep_salelist_submit').removeAttr('disabled');
          $('#rep_salelist_submit').text('submit');
          repList.showButton = false;
        })
      }
    },
    exportList:function(){
      if($('#test_list').children().length > 0){
        $('#replishmentLists table').csvExport({
          title:$('#selected_shop option:selected').text()+' sales list from '+this.startTime+' to '+this.endTime
        });
      }else{
        alert('you can not exort the lists');
      }
    }
  }
});
