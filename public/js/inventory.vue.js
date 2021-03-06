
var parent = new Vue({
  el:'#stockTake_HQ',
  data:{
    stocks:[],
    search:'',
    styleObj:{
      'border':'1px solid red',
      'height':'30vh',
      'overflow':'auto'
    },
	  user_id:$('.stock_userID').val(),
    loading:true,
  },
  created(){

    axios({
           method:'get',
           url:stockMan+'hq_inventoryList',
           crossDomain:true
         }).then(function(res){
      console.log(res.data)
      res.data.forEach((e)=>{
        parent.loading = false;
        e.search = e.name.toLowerCase().concat(e.reference.toString().toLowerCase());

      });

      parent.stocks = res.data;


    }).catch(function(error){
      console.log(error)
    });
  },
  methods:{

    tt:function(index,name,ref,stock_id,qty,$e){
      if(qty !== undefined && qty >= 0){
        console.log(name,ref,stock_id,qty)

      let btn =  document.querySelector(`.a${stock_id}`);
          btn.disabled = true;
          btn.innerText = 'Updating...';

          console.log(qty)
          axios({
            method:'post',
            url:stockMan+'saveToInventoryHistory',
            data:{
               web_stock_id:stock_id,
               reference:ref,
               name:name,
               qty:qty,
               user_id:parent.user_id,
               added:0

            }
          }).then((response)=>{
            Materialize.toast(`<h6><span class="green-text">${ref}</span> has been updated!</h6>`, 1000);
            console.log(response.data)
          console.log(  $('#stockTake_HQ form'));
              parent.stocks.forEach((e)=>{
                e.updateQty = '';
              })

              $('.input_qty').val("");

              btn.disabled = false;
              btn.innerText = 'Update';

          }).catch(function(error){
            console.log(error)
          });
      }else{
        Materialize.toast(`<h6 class='red-text'>Can Not Sumbit Empty Value!</h6>`, 1000);
      }
    }
  },
  computed:{
    searchLower:function(){
      return this.search.toLowerCase();
    },
    filterStocks:function(){
      return this.stocks.filter((stock)=>{
        return stock.search.match(this.searchLower);
      })
    }
  }

});


var add_missing = new Vue({
    el:'#adding_modal',
    data:{
        name:'',
        ref:'',
        qty:'',
        user_id:$('.stock_userID').val(),
        isdisabled:false
    },
    methods:{
        reset:function(){
            this.name="";
            this.ref="";
            this.qty="";
        },
        addMissing:function(name,ref,qty){
            if(name !== '' && ref !== '' && qty > 0){
                this.isdisabled = true;
                axios({
                    method:'post',
                    url:stockMan+'saveToInventoryHistory',
                    data:{
                        web_stock_id:0,
                        name:name,
                        reference:ref,
                        qty:qty,
                        user_id:this.user_id,
                        added:1
                    }
                }).then((res)=>{
                    console.log(res);
                    Materialize.toast(`<h6 class='green-text'>Data Submited!</h6>`, 1000);
                    this.isdisabled = false;
                    this.reset();



                });

            }else{
                alert('Please input valid value');
            }
        }
    }
});
