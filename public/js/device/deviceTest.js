// console.log('shit');
//
// var device_update = new Vue({
//     el:'#devices_waitingForUpdate',
//     data:{
//         asda:1231,
//         devices:[]
//     },
//     beforeCreate:function(){
//         axios({
//             method:'get',
//             url:stockMan+'check_awaiting_update_devices',
//         }).then((res)=>{
//             //console.log(res.data)
//             res.data.forEach((e)=>{
//                 e.has_imei      = true;
//                 e.serial_number = '';
//                 e.dead          = false;
//                 e.disabled      = false;
//             })
//             this.devices = res.data;
//             console.log(this.devices)
//
//         });
//     },
//     methods:{
//         checkIMEi:function(imei){
//
//         }
//     }
// })
