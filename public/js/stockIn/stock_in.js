
var parent = new Vue({
  el:'#avaialble_stockIn',
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
    btn_disable:false
  },
  created(){
    axios({
           method:'get',
           url:stockMan+'available_for_stockIn',
           crossDomain:true
         }).then(function(res){
      console.log(res.data)
      res.data.forEach((e)=>{
        parent.loading = false;
        e.search = e.name.toLowerCase().concat(e.reference.toString().toLowerCase());
        e.warehouse_qty = Number(e.warehouse_qty);
      });

      parent.stocks = res.data;


    }).catch(function(error){
      console.log(error)
    });
  },
  methods:{

    tt:function(index,name,ref,stock_id,qty,$e){
      if(qty !== undefined && qty > 0){
        console.log(index,name,ref,stock_id,qty)

      // let btn =  document.querySelector(`.a${stock_id}`),
      // all_btn = $('#avaialble_stockIn btn');
      //     btn.disabled = true;
      //     btn.innerText = 'Updating...';
          this.btn_disable = true;
          this.filterStocks[index].warehouse_qty += Number(qty);

          axios({
            method:'post',
            url:stockMan+'save_update_stock',
            data:{
               web_stock_id:stock_id,
               reference   :ref,
               name        :name,
               stockIn_qty :qty,
               user_id     :parent.user_id,


            }
          }).then((response)=>{
            Materialize.toast(`<h6><span class="green-text">${ref}</span> has been updated!</h6>`, 1000);
             console.log(response.data)
          // console.log(  $('#stockTake_HQ form'));
              parent.stocks.forEach((e)=>{
                e.updateQty = '';
              })

              $('.input_qty').val("");

              this.btn_disable = false;

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
      if(this.searchLower.length >= 3){
          return this.stocks.filter((stock)=>{
            return stock.search.match(this.searchLower);
        });
      }else{
          return [];
      }
    }
  }

});
