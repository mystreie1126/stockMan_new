
var update_parts_barcode
    = new Vue({
        el:"#update_parts_barcode",
        data:{
            search:'',
            parts:[],
            brands:[],

        },

        created(){
            axios({
                method:'get',
                url:stockMan+'get_parts_brand'
            }).then((res)=>{
                update_parts_barcode.brands = res.data;
                console.log(res.data);
            })

        },
       watch:{
           search:function(newVal,oldVal){
               if(newVal.length >= 6){
                  axios({
                      method:'get',
                      url:stockMan+'check_parts_barcodes'
                  }).then((res)=>{
                      res.data.forEach((e)=>{
                          e.search = e.name.toLowerCase().concat(e.barcode.toString()).concat(e.id_product);
                          e.newbarcode = ' - ';
                          e.model=[];
                          e.identifier='';
                      })
                      update_parts_barcode.parts = res.data
                      console.log(update_parts_barcode.parts);
                  })
              }else{
                  update_parts_barcode.parts = []
              }

          },

       },
       computed:{
         searchLower:function(){
           return this.search.toLowerCase();
         },
         filterParts:function(){
               return this.parts.filter((part)=>{
                 return part.search.match(this.searchLower);
             });

         }
     },
     methods:{
         getModelByBrand:function(event,p){
             console.log(event)
             axios({
                 method:'post',
                 url:stockMan+"get_parts_model",
                 data:{
                     brand_id:Number(event.target.value)
                 }
             }).then((res)=>{
                 console.log(res.data);
                 p.model = res.data;
                 // this.parts[index].newbarcode = "P".concat(event.target.value);
                 p.newbarcode = p.newbarcode.replace(p.newbarcode.split('-')[0],event.target.value.toString());

             })
         },

         updateModel:function(event,p){
             console.log(event.target.value);
             // event.target.disabled = true;
             p.newbarcode = p.newbarcode.replace(p.newbarcode.split('-')[1],event.target.value.toString()+"/ ");


             // let arr = p.newbarcode.split('');
             // arr[arr.length - 1] = event.target.value;
             //
             // p.newbarcode = arr.join('').toString().replace(/\s/g, '').toUpperCase()+" ";
             // p.newbarcode = this.replaced(p.newbarcode,event.target.value);
         },
         generate_barcode:function(p){
             if(p.identifier.length > 0){
                 p.newbarcode = p.newbarcode.replace(p.newbarcode.split('/')[1]," "+p.identifier.toString());

                 axios({
                     method:'post',
                     url:stockMan+"setbarcode_topartsref",
                     data:{
                         barcode:"P"+p.newbarcode.replace(/\s/g, '').toUpperCase(),
                         id_product:p.id_product
                     }
                 }).then((res)=>{
                     console.log(res.data);
                     window.location.href="barcode_page";
                 })
            }else{
                alert('identifier can not be empty');
            }
         }

     }

    })
