
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
		//console.log(this.shop_id)
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
      //console.log(res.data);

    }).catch(function(error){
      console.log(error)
    });
  },
  methods:{

    tt:function(index,ref,stock_id,qty,$e){
      if(qty !== undefined && qty >= 0){
        console.log(ref,stock_id,qty)

      let btn =  document.querySelector(`.a${stock_id}`);
          btn.disabled = true;
          btn.innerText = 'Updating...';

          console.log(qty)
          axios({
            method:'post',
            url:stockMan+'saveToInventoryHistory',
            data:{
              reference:ref,
              web_stock_id:stock_id,
              qty:qty,
							user_id:parent.user_id
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
