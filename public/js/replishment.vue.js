var standard_rep = new Vue({
    el:'#standard_rep',
    data:{
        standard_list:[],
        showbtn:false,
    },
    methods:{
        get_standard_list:function(){
            let shop_id = $('#selected_standard_stock_shop').val();
            console.log(shop_id);

            if(Number(shop_id) != 0){
                removeHide($('.spinner-loader'));
                axios({
                    method:'post',
                    url:stockMan+'standard_replishment_list',
                    data:{
                        shop_id:shop_id
                    }
                }).then((res)=>{
                    addHide($('.spinner-loader'));
                    console.log(res.data);
                    if(res.data.length > 0){
                        this.showbtn = true;
                    }
                    this.standard_list = res.data;
                })
            }
        },
        submitStandardList:function(){
            let invalidNum = this.standard_list.filter((e)=>{
                return isNaN(Number(e.send)) == true || Number(e.send) < 0;
            });

             if(invalidNum.length == 0 && this.standard_list.length > 0){
                 console.log(invalidNum);
                 submit_once($('.submitStandardList'),'Submitting....');

                 axios({
                     method:'post',
                     url:stockMan+'save_standard_replist',
                     data:{
                        sheetData:JSON.stringify(this.standard_list)
                     }
                 }).then((res)=>{
                     this.showbtn = false;
                     this.standard_list = [];
                     console.log(res.data);
                 });
             }else{
                 alert('Please input valid number!');
             }
        },
        exportList:function(){
            let csv = objectToCSV(this.standard_list);
            downloadList(csv);
        }


    }
})










var sale_rep = new Vue({
    el:'#sale_rep',
    data:{
        sales_list:[],
        showbtn:false,
        //exportbtn:false
    },
    methods:{
        getsalesList:function(){
            removeHide($('.spinner-loader'));

            let startTime = $('#selected_start_date').val()+" 00:00:00",
                  endTime = $('#selected_end_date').val()+" 23:59:59",
                  shop_id = $('#selected_shop').val(),
                shop_name = $('#selected_shop :selected').text();
            axios({
                method:'post',
                url:stockMan+'getlistbysale',
                data:{
                    start_time:startTime,
                    end_time:endTime,
                    shop_id:shop_id
                }
            }).then((res)=>{
                console.log(res.data);
                this.sales_list = res.data.list;
                if(res.data.list.length > 0){
                    this.showbtn = true;
                }
                addHide($('.spinner-loader'));
            });
        },
        submitSaleList:function(){
           let invalidNum = this.sales_list.filter((e)=>{
               return isNaN(Number(e.suggest_send)) == true || Number(e.suggest_send) < 0;
           });

           if(invalidNum.length == 0 && this.sales_list.length > 0){
               console.log(invalidNum);
               submit_once($('.submitSaleList'),'Submitting....');

               axios({
                   method:'post',
                   url:stockMan+'save_replist',
                   data:{
                      sheetData:JSON.stringify(this.sales_list)
                   }
               }).then((res)=>{
                   this.showbtn = false;
                   this.sales_list = [];
                   console.log(res.data);
               });
           }else{
               alert('Please input valid number!');
           }
       },
       exportList:function(){
         let csv = objectToCSV(this.sales_list);
         downloadList(csv);
      }
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
