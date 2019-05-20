
var salesList = [];







$("#download-csv").click(function(e){
    e.preventDefault();
    table.download("csv", "data.csv");
});
function sendQty_number(qty){
  return Math.floor(Number(qty));
}


//
var repList = new Vue({
  el:'#replishmentLists',
  data:{
      lists:[],
      startTime:"",
      endTime:"",
      shop_id:"",
      shop_name:"",
      list_loading:false,
      submit_loading:false,
      showButton:false
  },
  created:function(){
    axios({
      method:'post',
      url:stockMan+'getSavedList',
      data:{
        rep_by_sale:1,
        custom_rep:0
      }
    }).then((res)=>{
      console.log(res.data);
    });
  },
  methods:{
    getList:function(){
      this.startTime = $('#selected_start_date').val()+" 00:00:00";
        this.endTime = $('#selected_end_date').val()+" 23:59:59";
        this.shop_id = $('#selected_shop').val();
      this.shop_name = $('#selected_shop :selected').text();

      if(this.shop_id !== null && new Date(this.startTime).getTime() > 0 && new Date(this.endTime).getTime() > 0 ){
         console.log(`shop id is ${this.shop_id} selected from ${this.startTime} to ${this.endTime}`);
         this.list_loading = true;
         axios({
           method:'post',
           url:stockMan+'getlistbysale',
           data:{
             start_time:this.startTime,
             end_time:this.endTime,
             shop_id:this.shop_id
           }
         }).then((res)=>{
              $('.regular_list_action').removeClass('hide');
              console.log(res.data.list);
               console.log(res.data.howMany)
              this.list_loading = false;

              $('.regular_list_action p').text(`${this.shop_name} Re-stock with ${res.data.howMany} types of product sold from ${this.startTime} to ${this.endTime}`);

              var table = new Tabulator('#regular_list',{
                data:res.data.list,
                layout:"fitColumns",
                height:"60vh",
                placeholder:"No Data Available",
                columns:[
                  {title:'Name',field:'name',width:400},
                  {title:'Barcode',field:'reference',width:200},
                  {title:'Sold',field:'soldQty',width:100,cssClass:"green-text"},
                  {title:'Standard',field:'standard',width:100,cssClass:"indigo-text"},
                  {title:'Checked Stock',field:'has_branch_stock',width:150,cssClass:"amber-text"},
                  {title:'Branch Qty',field:'branch_stock_qty',width:100,cssClass:"blue-text"},
                  {title:'Send',field:'suggest_send',width:150,editor:"input", validator:["min:0", "max:100", "integer"]},
                  {title:'webStockID',field:'web_stockID',width:10, visible:false},
                  {title:'posStockID',field:'pos_stockID',width:10, visible:false}


                ]
              })

              $('#filter-name').keyup(function(){
                table.setFilter('name','like',$('#filter-name').val())
              });

              $('#filter-barcode').keyup(function(){
                table.setFilter('reference','like',$('#filter-barcode').val())
              });

              //download

              //table.download("xlsx", "data.xlsx", {sheetName:"MyData"})

              $('#downloadExcel').click((e)=>{
                  table.download("xlsx", `${this.shop_name} from ${this.startTime} to ${this.endTime}.xlsx`, {sheetName:`${this.shop_name}`});
                  console.log('download xlsx');
              })

              $('#downloadCSV').click((e)=>{
                  table.download("csv", `${this.shop_name} from ${this.startTime} to ${this.endTime}.csv`);
                  console.log('downlad csv');
              })

            //   $('#downloadPDF').click((e)=>{
            //       table.download("pdf", "${this.shop_name} from ${this.startTime} to ${this.endTime}.pdf", {
            //         orientation:"portrait", //set page orientation to portrait
            //         title:"Dynamics Quotation Report", //add title to report
            //         autoTable:{ //advanced table styling
            //             styles: {
            //                 fillColor: [100, 255, 255]
            //             },
            //             columnStyles: {
            //                 id: {fillColor: 255}
            //             },
            //             margin: {top: 60},
            //         },
            //     });
            // });

            //submit
              $('#regular_list_submit').click(function(e){
                e.preventDefault();

                let myData = table.getData(true);

                $(this).attr('disabled','disabled');
                $(this).text('submitting.....');
                ifIsEmpty = myData.filter((e)=>{
                    return e.suggest_send == '';
                });

                if(ifIsEmpty.length > 0){
                    alert('Submit value can not be empty!');
                    $(this).removeAttr('disabled');
                    $(this).text('Submit');
                }else if(ifIsEmpty.length == 0){
                    axios({
                        method:'post',
                        url:stockMan+'save_replist',
                        data:{
                            sheetData:myData,
                           shop_id:repList.shop_id
                        }
                    }).then((res)=>{
                        console.log(res);
                    })
                }


              })



      });



      }else{
        alert('Please select a correct shop or date to proceed');
      }
    }



  }
});
