console.log(231231);


var newDevice = new Vue({
    el:'#newDevice',
    data:{
        basic:{
            imei:"",
            color:"",
            model:"",
            order_id:""
        }
    },
    mounted:function(){

    },
    computed:{
        storage:function(){
            $('.device_storage').change(function(){
                return $(this).val();
            });
        }
    },
    methods:{
        saveTo:function(){
            let storage   = $('.device_storage :selected').text(),
                brand     = $('.device_brands :selected').text(),
                condition = $('.device_condition :selected').text(),
                staffID   = $('.staffID').val();
                console.log(storage,brand,condition);

            if(this.basic.imei == ''||this.basic.color == ''||storage =='Choose Storage'||brand=='Available Brands'||condition=="Choose Condition"){
                alert('Fields can not be empty!');
            }else {
                $('.saveTo').attr('disabled','disabled');
                axios({
                    method:'post',
                    url:stockMan+'new_device_stockIn',
                    data:{
                        user    :staffID,
                        storage :storage,
                       condition:condition,
                        brand   :brand,
                        imei    :this.basic.imei.toUpperCase(),
                        color   :this.basic.color.toLowerCase(),
                        model   :this.basic.model.toLowerCase(),
                        order_id:this.basic.order_id,

                    }
                }).then((res)=>{
                    console.log(res.data);
                    window.location.href = "new_device";
                })

            }

        }
    }
})

//end of new Device


//transfer device

var aviable_devices = new Vue({
    el:'.all_devices',
    data:{
        all_devices:[],
        search:"",
        styleObj:{
         'border':'1px solid red',
         'overflow':'auto'
       },
    },
    created:function(){
        axios({
            method:'get',
            url:stockMan+'get_available_device_for_transfer'
        }).then((res)=>{
            console.log(res.data);
            res.data.forEach((e)=>{
                e.search = e.IMEI.toLowerCase().concat(e.brand.toLowerCase(),e.color.toLowerCase(),e.model.toLowerCase(),e.storage.toLowerCase(),e.condition.toLowerCase());
                e.notes = '';
            })
            aviable_devices.all_devices = res.data;
            console.log(res.data);

        })
    },
    computed:{
        searchLower:function(){
            return this.search.toLowerCase();
       },
       filterDevice:function(){
         if(this.searchLower.length > 2){
             return this.all_devices.filter((e)=>{
               return e.search.match(this.searchLower);
             })
         }else{
             return [];
         }
       },
   },
   methods:{
       assignTo:function(device_id,notes){
           assignShop = $('.assignShop :selected').val(),
           user_id     = $('.staffID').val();
           console.log(device_id,notes);

          if(isNaN(Number(assignShop))){
              alert('Please select valid shop');
          }else{
              $('.assignTo').attr('disabled','disabled');
             axios({
                 method:'post',
                 url:stockMan+'transfer_device',
                 data:{
                   shop_id  :assignShop,
                   user_id  :user_id,
                   device_id:device_id,
                   notes    :notes
                 }
             }).then((res)=>{
                 console.log(res.data);
                 window.location.href = "deviceTransfer";
             });
          }

       }
   }
});

//send device

$(document).ready(function(){
    $('.send_device').click(function(e){
        e.preventDefault();
        submit_once($('.send_device'),'sending.....');

        let form =$(this).parent();
        form.submit();
    })
})
