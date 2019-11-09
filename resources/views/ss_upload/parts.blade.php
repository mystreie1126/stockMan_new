@extends('template')
@if(Auth::check())
@section('content')
<div>
    <div class="missmatches">
        <p class="center">@{{message}}</p> 
        
        <ul class="collapsible" data-collapsible="accordion">
            <li v-for ="e in missmatches">
           
            <div class="collapsible-header" v-if="e.length > 0"><i class="material-icons teal-text">build</i> @{{e.length}} missmatches</div>
                <div class="collapsible-body center">
                    <div v-if="e.length > 0">
                        <button class="btn amber" @click.prevent="rebuff_doublecheck(e)" :disabled="disabled">Let Branch Doublecheck</button>
                        <table class="centered">
                            <thead>
                                <tr>
                                    <th>shop</th>
                                    <th>Parts Name</th>
                                    <th>Given</th>
                                    <th>Rockpos</th>                               
                                    <th>update as </th>
                                    <th>action</th>
                                    <th>Reason for Merge</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item,index) in e" >
                                    <td>@{{item.shopname}}</td>
                                    <td tyle="width:5%">@{{item.partsname}}</td>
                                    <td>@{{item.given_qty}}</td>
                                    <td>@{{item.rockpos_qty}}</td>     
                                    <td><input type="number" v-model="item.updated_qty" class="center" placeholder="default as 0"></td>
                                    <td><div class="btn" @click.prevent="merge(item,index,e)" :disabled="disabled">merge</div></td>
                                    <td style="width:25%"><input type="text" v-model="item.reason" class="red-text center" placeholder="can leave empty"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </li>
        </ul>
        
    </div>
</div>
@stop
@push('upload_ss_parts')
<script>
    var missmatch = new Vue({
        el:'.missmatches',
        data:{
            missmatches:[],
            message:'',
            disabled:false
        },
        created(){
            axios({
                method:'get',
                url:api_endpoint+'parts-stocktake-upload/admin/if-has-upload'
            }).then((res)=>{
                var response = res.data.data;
                missmatch.message = response.msg;
                missmatch.missmatches = response.data;
                console.log(missmatch.missmatches)
            })
        },
        methods:{
            merge:function(obj,index,ele){
                //only need to pos upload id, updated qty and reason 
                console.log(ele,index)
                if(Number(obj.updated_qty) >= 0 && Number.isInteger(Number(obj.updated_qty)) == true){
                    this.disabled = true;
                    axios({
                        method:'post',
                        url:api_endpoint+'parts-stocktake-upload/admin/merge-to-pos',
                        data:obj
                    }).then((e)=>{
                        if(e.data.status == 'success'){
                            this.disabled = false;
                            console.log(e.data)
                            ele.splice(index,1);
                        }
                    }).catch((err)=>{
                        console.log(err)
                    })
                }else{
                    alert('has to be positve integer')
                }
            },
            rebuff_doublecheck:function(ele){
                let upload_ids = ele.map((e)=>e.id);
                //console.log(upload_ids)
                let r = confirm('DO you wanna send these part to the shop for doublechecking?');
                if(r == true){
                    axios({
                        method:"post",
                        url:api_endpoint+'parts-stocktake-upload/admin/rebuff-to-doublecheck',
                        data:upload_ids
                    }).then((res)=>{
                        if(res.data.status == 'success'){
                            console.log(res.data)
                            this.disabled = true;
                            location.reload();
                        }
                    })
                }

            }
        }
    })
</script>
@endpush
@endif
