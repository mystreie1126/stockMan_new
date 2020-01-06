@extends('template')
@section('content')
@if(Auth::check())

<div id="someshit" class="container row">
    <div class="input-field col s6">
        <select class="chooseshops">
          <option value="" disabled selected>Choose your option</option>
          @foreach($shops as $shop)
            <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
          @endforeach
        </select>
        <label>Choose a shop</label>
    </div>
   
    <button class="btn col s2" style="transform:translateY(50%)" @click="choose_shop_products">Go</button>
    
    <p class="col s12">
        <input type="text" v-model="search" placeholder="SEARCH BY BARCODE OR NAME" class="searchable">

    </p>
    <table class=" highlight centered col s12" v-if="data.length > 0">
        <thead>
            <tr>
                <th>shopname</th>
                <th>Name</th>
                <th>Barcode</th>
                <th>store Qty</th>
                <th>Standard</th>
               
            </tr>
        </thead>
        <tbody>
            
            <tr v-for="(list,index) in filterData">
                <td>@{{list.shopname}}</td>
                <td>@{{list.name}}</td>
                <td>@{{list.reference}}</td>
                <td>                  
                    <input type="number" v-model="list.store_qty" style="width:40%" class="center indigo-text">
                    <a class="btn-floating btn-small indigo" @click="update_store_qty(index)">
                        <i class="large material-icons">mode_edit</i>
                    </a>
                </td>
                <td>
                    <input type="number" v-model="list.standard_quantity"  style="width:40%" class="center green-text">
                    <a class="btn-floating btn-small green" @click="update_standard_qty(index)">
                        <i class="large material-icons">mode_edit</i>
                    </a>
                </td>
               
            </tr>
            
          </tbody>
       
        
    </table>
</div>


@push('detail_product_all')
<script>
    console.log(api_endpoint+'detail_product_all')
    var dd = new Vue({
        el:'#someshit',
        data:{
            data:[],
            checkedShops:[],
            search:''
        },
       
        methods:{
            choose_shop_products:function(){
                let shop_id = Number($('.chooseshops :selected').val());
                if(Number(shop_id) == 0){
                    alert('choose a valid shop')
                }else{
                        axios({
                        methods:'get',
                        url:api_endpoint+'detail_product_all/'+shop_id
                    }).then((res)=>{
                        res.data.forEach((e)=>{
                            e.searchStr = e.name.toString().toLowerCase().concat(e.reference.toString().toLowerCase());
                            e.store_stock = Number(e.store_qty);
                            e.standard = Number(e.stadnard_quantity);
                           
                        })
                        dd.data = res.data
                        console.log(dd.data)
                    })
                }
                
            },
            update_store_qty:function(index){
               
                if(Number(this.data[index].store_qty) >= 0){
                    axios({
                        method:'put',
                        data:{
                            updated_quantity:Number(this.data[index].store_qty),
                            stock_id:this.data[index].stock_id
                        },
                        url:api_endpoint+'detail_product_all/store_qty'
                    }).then((e)=>{
                        console.log(e)
                        if(e.data == 'success'){
                            alert('updated store quantity')
                        }else{
                            alert('updated failed')
                        }
                    })
                }else{
                    alert('can not less than 0')
                }
            },
            update_standard_qty:function(index){
                
                if(Number(this.data[index].standard_quantity) >= 0){
                    axios({
                        method:'put',
                        data:{
                            updated_quantity:Number(this.data[index].standard_quantity),
                            standard_id:this.data[index].standard_id
                        },
                        url:api_endpoint+'detail_product_all/standard_qty'
                    }).then((e)=>{
                        if(e.data == 'success'){
                            alert('updated standard')
                        }else{
                            alert('updated failed')
                        }
                    })
                }else{
                    alert('can not less than 0')
                }
            }
        },
        computed:{
			searchLower:function(){
		      return this.search.toLowerCase();
		    },
			filterData:function(){
		          return this.data.filter((e)=>{
		            return e.searchStr.match(this.searchLower);
				});
		    }
		}
    })
</script>
@endpush
@stop
@else
    <h3>Please Loggin</h3>
@endif
