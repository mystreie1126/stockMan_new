console.log('invoice');

var invoice = new Vue({
    el:'#invoice',
    data:{
        date:'',
        billing_address:'',
        shipping_address:'',
        showList:false,
        lists:[],
        email:'',
        order_reference:'',
        fullname:''
    },
    methods:{
        add:function(){
            this.showList = true;
            this.lists.push({
                ref:'',
                name:'',
                qty:'',
                tax:'',
            });
        },
        generateInvoice:function(){
            console.log(this.lists);
        },
        remove:function(index){
            this.lists.splice(index,1);
        },
        send:function(){

            let refs = this.lists.filter((e)=>{
                return e.ref == '';
            })

            let names = this.lists.filter((e)=>{
                return e.name == '';
            })

            let qtys = this.lists.filter((e)=>{
                return e.qty == '';
            })

            let all_taxes = this.lists.filter((e)=>{
                return e.tax == '';
            })


            if(this.lists.length > 0
                && refs.length == 0
                && names.length == 0
                && qtys.length == 0
                && all_taxes.length == 0
                && this.date !== ''
                && this.email !== ''

                )
            {
                this.lists.forEach((e)=>{
                    e.taxes = Number(Number(e.price) * Number(e.tax/100)).toFixed(2);
                    e.total = Number(e.price * e.qty);
                    e.price_tax_excl = Number(e.price - Number(e.price*e.tax/100)).toFixed(2);
                });
                axios({
                    method:'post',
                    url:stockMan+'send_invoice',
                    data:{
                        date:this.date,
                        billing_address:this.billing_address,
                        shipping_address:this.shipping_address,
                        email:this.email,
                        name:this.fullname,
                        order_ref:this.order_reference,
                        invoice_id:$('.last_id').val(),
                        lists:JSON.stringify(this.lists),
                        total_tax:this.total_tax,
                        total_price:this.total_price,
                    }
                }).then((e)=>{
                    console.log(e.data);
                })
            }else {
                alert('Please fill all the required field!');
            }
        },
        tax_percentage:function(tax){
            return tax/100;
        },
        taxes:function(price,tax){
            return Number(price * tax/100).toFixed(2);
        },
        price_tax_excl:function(price,tax){
            return Number(price - Number(price*tax/100)).toFixed(2);
        }

    },
    computed:{

        total_price:function(){
             let a =  this.lists.map((e)=>{
                return Number(e.qty)*Number(e.price);
            });
            //return a;

            return a.reduce(function(c,d){
                return c + d;
            });
        },
        total_tax:function(){
            let arr = this.lists.map((a)=>{
                return Number(Number(a.price) * Number(a.tax/100)).toFixed(2);
            });
            return arr.reduce(function(e,f){
                return Number(Number(e) + Number(f)).toFixed(2);
            });
        },

    }


})
