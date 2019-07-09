
var device_stockIn_pool = new Vue({
    el:'#device_pool_stockIn',
    data:{
        brandNew:false,
        condition:'Pre Owned',
        // mobile_device_only:true,


        basic:{
            device_types:1,
            supplier_order_id:"",
            device_model_name:""
        },

        jiance:{
            brandnew_brand_category_id:'',
            preown_brand_category_id:'',
            device_model_name:""
        }

    },
    watch:{
        brandNew(){
            this.condition = 'Pre Owned';
            if(this.brandNew == true){
                this.condition = "Brand New";
            }

        },
        device_types(){
            if(this.device_types == 2){
                this.mobile_device_only = true;
            }else{
                this.mobile_device_only = false;
            }
        }
    },
    methods:{
        saveTo_pool:function(is_brandNew,is_preOwn,brand_category_id,brand_name){
            axios({
                method:'post',
                url:stockMan+'create_mobile_device',
                data:{
                    staff_id          :document.querySelector('.staffID').value,
                    brandNew          :is_brandNew,
                    preOwn            :is_preOwn,
                    device_type       :this.basic.device_types,
                    model_category_id :brand_category_id,
                    supplier_order_id :this.basic.supplier_order_id.toLowerCase(),
                    model_name        :this.jiance.device_model_name.toLowerCase().replace(/ /g,''),
                    brand_name        :brand_name


                }
            }).then((res)=>{
                console.log(res.data);
                window.location.href = "create_new_device";
            })
        },
        create_preown:function(){
            if(Number(this.jiance.preown_brand_category_id) > 0 && this.jiance.device_model_name !== null && this.jiance.device_model_name !== ''){
                submit_once(document.querySelector('.create_preown'),'loading...');
                let is_brandNew = 0,
                    is_preOwn   = 1,
                    brand_name  = $('.pre_own_select :selected').text();
                this.saveTo_pool(is_brandNew,is_preOwn,this.jiance.preown_brand_category_id,brand_name);
            }else{
                alert('Device brand or model name can be empty!');
            }
        },
        create_brandnew:function(){
            if(Number(this.jiance.brandnew_brand_category_id) > 0 && this.jiance.device_model_name !== null && this.jiance.device_model_name !== ''){
                submit_once(document.querySelector('.create_brandnew'),'loading...');
                let is_brandNew = 1,
                    is_preOwn   = 0,
                    brand_name  = $('.brand_new_select :selected').text();
                this.saveTo_pool(is_brandNew,is_preOwn,this.jiance.brandnew_brand_category_id,brand_name);
            }else{
                alert('Device brand or model name can be empty!');
            }
        }
    }
})
