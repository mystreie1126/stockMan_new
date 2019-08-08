console.log('price');

var partner_order_history = new Vue({
    el:'#partner_order_history',
    data:{
        orders:[],
        reps:[],
        from:'',
        to:'',
        shopname:'',

    },
    computed:{

    },
    methods:{
        check:function(){
            removeHide($('.pre-loader'));
            this.orders = [];
            this.reps = [];
            let partner_id = $('#selected_partner').val();
            let startTime = $('.selected_start_date').val()+" 00:00:00",
                  endTime = $('.selected_end_date').val()+" 23:59:59";
                  console.log(partner_id);
            axios({
                method:'post',
                url:stockMan+'get_partner_order_by_date',
                data:{
                     startTime:startTime,
                       endTime:endTime,
                    partner_id:partner_id
                }
            }).then(function(res){
                console.log(res.data);
                partner_order_history.orders   = res.data.orders;
                partner_order_history.reps     = res.data.reps;
                partner_order_history.from     = res.data.from;
                partner_order_history.to       = res.data.to;
                partner_order_history.shopname = res.data.name;
                addHide($('.pre-loader'));
            })

        },
        wholesale:function(order){
         return Number(order.order_detail.map((e)=>e.wholesale * e.quantity).reduce((a,b)=>a+b)).toFixed(2);
        },
        rep_wholesale:function(reps){
            return reps.map((e)=>e.total_send*e.wholesale).reduce((a,b)=>a+b);
        },
        missing_order_wholesale:function(order){
            return order.filter((e)=>e.wholesale <= 0).map((e)=>e.barcode);
        }
    }
})
