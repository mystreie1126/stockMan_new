
var table = new Tabulator("#replishment_list", {
    data:[], //set initial table data
});



$('#createSalesList').click((e)=>{
    e.preventDefault();
    //table.setData(tableData2);
    $('.pre-loader').removeClass('hide');
    let startTime = $('#selected_start_date').val()+" 00:00:00",
          endTime = $('#selected_end_date').val()+" 23:59:59",
          shop_id = $('#selected_shop').val(),
        shop_name = $('#selected_shop :selected').text();
        // console.log(123);
    if(shop_id !== null && new Date(startTime).getTime() > 0 && new Date(endTime).getTime() > 0 ){
       $.ajax({
           type:'post',
           url:stockMan+'getlistbysale',
           dataType:'json',
           data:{
               start_time:startTime,
               end_time:endTime,
               shop_id:shop_id
           },
           success:function(res){
               console.log(res);
               $('.list_action').html('<button class="saveTo_salesList btn">Submit</button>');
               $('.download').html('<button class="btn right download_btn">Export CSV</button>')
               var columns = [
                   {title:'Name',field:'name',width:300,align:"center"},
                   {title:'Barcode',field:'reference',width:200,align:"center"},
                   {title:'Sold',field:'soldQty',width:100,align:"center"},
                   // {title:'Standard',field:'standard',width:100,align:"center",cssClass:"indigo-text"},
                   // {title:'Correct Stock',field:'has_branch_stock',width:200,align:"center"},
                   // {title:'Actual Quantity',field:'branch_stock_qty',width:200,align:"center",cssClass:"blue-text"},
                   {title:'Send',field:'suggest_send',width:100,editor:"number",align:"center",cssClass:'indigo-text'},
                   {title:'Shop Name',field:'shop_name',width:250,align:"center"},
                   {title:'selected_from',field:'selected_from',width:2,visible:false},
                   {title:'selected_to',field:'selected_to',width:2,visible:false},
                   {title:'webStockID',field:'web_stockID',width:2,visible:false},
                   {title:'posStockID',field:'pos_stockID',width:2,visible:false}

               ];

               table.setColumns(columns);
               table.setData(res.list);
               //$('.message').text(`Replishment By Sale - selected ${shop_name} with sales from ${startTime} to ${endTime}`);
               $('.message').html(`<h5>Replishment By Sale - selected Branch: <span class="indigo-text">${shop_name}</span><h5>
                                   <h5>Selected Sales Date From: <span class="teal-text">${startTime}</span> to: <span class="teal-text">${endTime}</span><h5>
                                 `)
               $('.pre-loader').addClass('hide');

           }
       })
   }else{
       alert('please select proper value to get list');
       $('.pre-loader').addClass('hide');

   }

});

//submit sales replist list
$('.list_action').on('click','.saveTo_salesList',function(e){
    e.preventDefault();
    submit_once(this,'loading......');

    let data = table.getData(true);
    data.forEach((e)=>e.suggest_send = Number(e.suggest_send));
    let invalidNum = data.filter((e)=>{
        return isNaN(e.suggest_send) == true || e.suggest_send < 0;
    })

    console.log(data.map((e)=>{return e.suggest_send}))

    if(invalidNum.length == 0){
        $.ajax({
            method:'post',
            dataType:'json',
            url:stockMan+'save_replist',
            data:{
                sheetData:JSON.stringify(data),
            },
            success:function(res){
                console.log(res);
                reset_button($('.saveTo_salesList'));

                $('.list_action').html('');
                $('.download').html('');
                $('.message').html('<h5 class="green-text">Success Submited!</h5>').fadeOut(5000);                table.setData([]);
            }
        })
    }else{
        alert('Send quantity has to be a positive number!')
    }
});

//download


// table.download("csv", "data.csv"); //download table data as a CSV formatted file with a file name of data.csv

$('.download').on('click','.download_btn',function(e){
    e.preventDefault();
    let data = table.getData(true);
    let name = `${data[0].shop_name} list`;
    table.download('csv',`${name}.csv`);
});
/*================================standard sending list==============================================================*/

$('#createStandardList').click(function(e){
    e.preventDefault();
    let shop_id = $('#selected_standard_stock_shop').val();

    if($('.pre-loader').hasClass('hide')){
        $('.pre-loader').removeClass('hide');
    }

    if(Number(shop_id) != 0){
        console.log(shop_id);
        $.ajax({
            type:'post',
            url:stockMan+'standard_replishment_list',
            data:{
                shop_id:shop_id
            },
            success:function(res){
                console.log(res);

                $('.list_action').html('<button class="saveTo_standardList btn">Submit</button>');
                $('.download').html('<button class="btn right download_btn">Export CSV</button>')

                var columns = [
                    {title:'Name',field:'name',width:300,align:"center"},
                    {title:'Barcode',field:'reference',width:200,align:"center"},
                    {title:'Standard',field:'standard',width:100,align:"center",cssClass:"green-text"},
                    {title:'Stock Qty',field:'quantity',width:200,align:"center",cssClass:"amber-text"},
                    {title:'Send',field:'send',width:100,editor:"number",align:"center",cssClass:'indigo-text'},
                    {title:'Shop Name',field:'shop_name',width:250,align:"center"},
                    {title:'webStockID',field:'webStockID',width:2,visible:false},
                    {title:'posStockID',field:'branchStockID',width:2,visible:false}

                ];
                if(!$('.pre-loader').hasClass('hide')){
                    $('.pre-loader').addClass('hide');
                }
                table.setColumns(columns);
                table.setData(res);

            }
        })
    }else{
        if(!$('.pre-loader').hasClass('hide')){
            $('.pre-loader').addClass('hide');
        }
        alert('Selected Shop can not be null!');
    }

});


$('.list_action').on('click','.saveTo_standardList',function(e){
    console.log('standard');

    let data = table.getData(true);
    data.forEach((e)=>e.send = Number(e.send));
    let invalidNum = data.filter((e)=>{
        return isNaN(e.send) == true || e.send < 0;
    })



    if(invalidNum.length == 0){

        submit_once(this,'loading......');

        $.ajax({
            method:'post',
            dataType:'json',
            url:stockMan+'save_standard_replist',
            data:{
                sheetData:JSON.stringify(data)
            },
            success:function(res){
                console.log(res);

                $('.list_action').html('');
                $('.download').html('');
                $('.message').html('<h5 class="green-text">Success Submited!</h5>').fadeOut(5000);
                table.setData([]);
            }
        })
    }else{
        alert('Send quantity has to be a positive number!')
    }



})

/*================================custom sending list==============================================================*/


var custom_rep = new Vue({
    el:'#custom_rep',
    data:{
        custom_lists:[],
        search:''
    },
    methods:{
        ajax_getStock:function(){
            let shop_id = $('#selected_custom_stock_shop').val();
            if(shop_id == null || shop_id == 41){
                alert('Please select a valid shop');
            }else{
                axios({
                    method:'post',
                    url:stockMan+'custom_get_rep_data',
                    data:{
                        shop_id:shop_id,
                        search:this.search
                    }
                }).then((res)=>{
                    if(res.data.exsists == 1){
                        console.log(res.data.result);
                        this.custom_lists.push(res.data.result);
                    }else{
                        alert('can not find this item');
                    }


                })
            }

        },
        submitThis:function(){
            if(this.custom_lists.length > 0 && this.faultySend_qty.length == 0){
                submit_once($('.save_custom_rep'),'saving....');
                let list = JSON.stringify(this.custom_lists);
                axios({
                    method:'post',
                    url:stockMan+'custom_rep_data_save',
                    data:{
                        list:list
                    }
                }).then((res)=>{
                    console.log(res.data);
                    this.custom_lists = [];
                    reset_button($('.save_custom_rep'),'submit');
                })
            }else{
                alert('list is empty!');
            }
        },
        sortName:function(){
            this.custom_lists.sort((a,b)=>(a.name > b.name)? -1:1);
        },
        sortRef:function(){
            this.custom_lists.sort((a,b)=>(a.ref > b.ref)? 1: -1);
        },
        sortSend_qty:function(){
            this.custom_lists.sort((a,b)=>(a.send > b.send)? 1: -1);
        },
        sortShop:function(){
            this.custom_lists.sort((a,b)=>(a.shopname > b.shopname)? 1: -1);
        },
        deleteThis:function(index){
            this.custom_lists.splice(index,1);
        },
    },
    computed:{
        faultySend_qty:function(){
            return this.custom_lists.filter((e)=>Number(e.send <= 0));
        }
    }
});
