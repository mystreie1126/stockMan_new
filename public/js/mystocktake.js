
$(document).ready(function(){
    $.ajax({
        method:'post',
        url:stockMan+'getMyStockTake',
        data:{
            user_id:$('#mystocktake input').val()
        },
        success:function(res){
            console.log(res);

            var table = new Tabulator('.mystocktake_table',{
              data:res,
              layout:"fitColumns",
              height:"100vh",
              placeholder:"No Data Available",
              columns:[
                {title:'Barcode',field:'reference',width:100},
                {title:'Name',field:'name',width:300,cssClass:"indigo-text"},
                {title:'Updated Quantity',field:'current_quantity',width:200,cssClass:"green-text",align:"center"},

                {title:'User',field:'user',width:100,cssClass:"indigo-text"},

                {title:'Date_updated',field:'created_at',width:350,cssClass:"amber-text"},


              ]
            })
        }

    })
});
