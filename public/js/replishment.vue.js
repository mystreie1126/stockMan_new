
var salesList = [];
const replishmentApi = 'https://calm-anchorage-96610.herokuapp.com/http://stockmangagerapi.funtech.ie/api/';
//const replishmentApi = 'http://localhost/project/laravel/newStockApi/public/api/';


var sss = [
  {id:1, name:"Billy Bob asdadsasdadas asdadsasdadas asdadsasdadas", age:12, gender:"male", height:200, col:"red", dob:"14/05/2010"},
  {id:2, name:"Jenny Jane", age:42, gender:"female", height:142, col:"blue", dob:"30/07/1954"},
  {id:3, name:"Steve McAlistaire", age:35, gender:"male", height:176, col:"green", dob:"04/11/1982"},
];

console.log(sss);
var table = new Tabulator("#example-table", {
  data:sss,
  height:"311px",
   layout:"fitColumns",
   columns:[
       {title:"Name", field:"name", width:400, editor:"input", validator:"required"},
       {title:"Gender", field:"gender", editor:"input", validator:["required", "in:male|female"]},
       {title:"Rating", field:"height",  editor:"input", align:"center", width:100, editor:"input", validator:["min:0", "max:5", "integer"]},
       {title:"Favourite Color", field:"col", editor:"input", validator:["minLength:3", "maxLength:10", "string"]},
   ],
   validationFailed:function(cell, value, validators){
       //cell - cell component for the edited cell
       //value - the value that failed validation
       //validatiors - an array of validator objects that failed

       //take action on validation fail
   },
});

var myData = table.getData(true);



$("#download-csv").click(function(e){
    e.preventDefault();
    table.download("csv", "data.csv");
});
function sendQty_number(qty){
  return Math.floor(Number(qty));
}


//
// var repList = new Vue({
//   el:'#replishmentLists',
//   data:{
//       lists:[],
//       startTime:"",
//       endTime:"",
//       shop_id:"",
//       list_loading:false,
//       submit_loading:false,
//       showButton:false
//   },
//   created:function(){
//     axios({
//       method:'post',
//       url:replishmentApi+'getSavedList',
//       data:{
//         rep_by_sale:1,
//         custom_rep:0
//       }
//     }).then((res)=>{
//       console.log(res.data);
//     });
//   },
//   methods:{
//     getList:function(){
//       this.list_loading = true;
//       this.startTime = $('#selected_start_date').val()+" 00:00:00";
//         this.endTime = $('#selected_end_date').val()+" 23:59:59";
//         this.shop_id = $('#selected_shop').val();
//
//       if(this.shop_id !== null && new Date(this.startTime).getTime() > 0 && new Date(this.endTime).getTime() > 0 ){
//          console.log(`shop id is ${this.shop_id} selected from ${this.startTime} to ${this.endTime}`);
//          axios({
//            method:'post',
//            url:replishmentApi+'getlistbysale',
//            data:{
//              start_time:this.startTime,
//              end_time:this.endTime,
//              shop_id:this.shop_id
//            }
//          }).then((res)=>{
//            console.log(res.data);
//               let html = '';
//               let all_list = res.data.sale.concat(res.data.re_instock);
//               console.log(all_list);
//
//               var lists = all_list.filter((e,i)=>{
//                  return e !== undefined;
//                });
//
//              lists.forEach((e,i)=>{
//                if(e.checked !== null){
//                   e.branch_qty = e.branch_qty;
//                   e.send = (e.standard_qty - e.branch_qty < 0) ? 0 : e.standard_qty - e.branch_qty;
//                }else{
//                  e.branch_qty = "No";
//                  e.send = "";
//                }
//
//              html += "<tr>"+
//                         "<td>"+e.name+"</td>"+
//                         "<td>"+e.ref+"</td>"+
//                         "<td class='green-text'>"+e.soldQty+"</td>"+
//                         "<td class='indigo-text'>"+e.standard_qty+"</td>"+
//                         "<td class='amber-text'>"+e.branch_qty+"</td>"+
//                         "<td class='input-field center'>"+
//                           "<input type='text' class='send_qty center' value="+e.send+">"+
//                           "<input type='hidden' class='l_ref' value="+e.ref+">"+
//                           "<input type='hidden' class='l_standard' value="+e.standard_qty+">"+
//                           "<input type='hidden' class='l_branch_stock_id' value="+e.branchStock_ID+">"+
//                           "<input type='hidden' class='l_web_stock_id' value="+e.webStock_ID+">"+
//                         "</td>"
//                      "</tr>"
//
//            });
//            $('#test_list').html(html);
//            this.list_loading = false;
//            this.showButton = true;
//          });
//
//
//
//       }else{
//         alert('Please select a correct shop or date to proceed');
//       }
//     },
//
//     saveTheList:function(){
//       let   valid_arr = [],
//           invalid_arr = [];
//       $('#test_list tr').each(function(index,ele){
//
//           if(sendQty_number($(ele).find('.send_qty').val()) >= 0 && $(ele).find('.send_qty').val() !== ''){
//               valid_arr.push({
//                         send:sendQty_number($(ele).find('.send_qty').val()),
//                          ref:sendQty_number($(ele).find('.l_ref').val()),
//                     standard:sendQty_number($(ele).find('.l_standard').val()),
//              branch_stock_id:sendQty_number($(ele).find('.l_branch_stock_id').val()),
//                 web_stock_id:sendQty_number($(ele).find('.l_web_stock_id').val()),
//                      shop_id:repList.shop_id,
//                     uploaded:0,
//                  rep_by_sale:1,
//                   rep_custom:0
//               });
//           }else{
//               invalid_arr.push($(ele).find('.send_qty').val());
//           }
//       });
//       console.log( valid_arr,invalid_arr);
//       if(invalid_arr.length > 0){
//         alert('send Quantity can not be empty and less than 0!');
//       }else if($('#test_list').children().length > 0 && invalid_arr.length == 0){
//         //sumbit here
//         $('#rep_salelist_submit').attr('disabled','disabled');
//         $('#rep_salelist_submit').text('loading...');
//         console.log('saving');
//
//         axios({
//           method:'post',
//           url:replishmentApi+'save_replist',
//           data:JSON.stringify( valid_arr)
//         }).then((res)=>{
//           console.log(res.data);
//           $('#test_list tr').remove();
//           $('#rep_salelist_submit').removeAttr('disabled');
//           $('#rep_salelist_submit').text('submit');
//           repList.showButton = false;
//         })
//       }
//     },
//     exportList:function(){
//       if($('#test_list').children().length > 0){
//         $('#replishmentLists table').csvExport({
//           title:$('#selected_shop option:selected').text()+' sales list from '+this.startTime+' to '+this.endTime
//         });
//       }else{
//         alert('you can not exort the lists');
//       }
//     }
//   }
// });
