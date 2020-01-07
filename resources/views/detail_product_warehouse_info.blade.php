@extends('template')
@section('content')
@if(Auth::check())

<div id="pussy" class="container row">
    <div class="input-field col s6">
        <select class="chooseshops">
          <option value="" disabled selected>select Catgory</option>
          @foreach($catas as $cata)
            <option value="{{$cata->id}}">{{$cata->category_name}}</option>
          @endforeach
        </select>
        <label>Choose by category</label>
    </div>
    
    <button class="btn col s2 indigo" style="transform:translateY(50%)" @click="choose_by_category">Go</button>
    
    <p class="col s12">
        <input type="text" v-model="search" placeholder="SEARCH BY BARCODE OR NAME" class="searchable">
    </p>
    <p v-if="data.length > 0" class="col s12">
        Total <span class="indigo-text">@{{howmany}}</span> types of product
    </p>
    <table class=" highlight centered col s12" v-if="data.length > 0">
        <thead>
            <tr>
                <th><a style="cursor:pointer" @click="sortorder('supplier')" class="btn-small">Supplier</a></th>
                <th><a class="btn-small">Name</a></th>
                <th><a class="btn-small">Barcode</a></th>
                <th><a style="cursor:pointer" class="btn-small" @click="sortorder('cost')">Cost</a></th>
                <th><a style="cursor:pointer" class="btn-small red-text" @click="sortorder('wholesale')">Wholesale</a></th>
                <th><a class="btn-small">Retail</a></th>   
                <th><a class="btn-small">Category</a></th>
                <th><a style="cursor:pointer" @click="sortorder('warehouse_stock')" class="btn-small">Warehouse Stock</a></th>
                <th><a style="cursor:pointer" @click="sortorder('branch_total_stock')" class="btn-small">All Branch Stock</a></th>
                
            </tr>
        </thead>
        <tbody>
            <tr v-for="(list,index) in filterData">
                
                @if(Auth::User()->id == 1 || Auth::User()->id ==2)
                    <td>
                        <input type="text" class="center" v-model="list.supplier">
                    </td>
                @else
                    <td>
                        @{{list.supplier}}
                    </td>
                @endif
                
                <td>@{{list.name}}</td>
                <td>@{{list.reference}}</td>
                @if(Auth::User()->id == 1 || Auth::User()->id ==2)
                    <td>
                        <input type="text" class="center teal-text" v-model="list.cost">
                    </td>
                @else
                    <td>
                        @{{list.cost}}
                    </td>
                @endif
                @if(Auth::User()->id == 1 || Auth::User()->id ==2)
                    <td>
                        <input type="number" class="center red-text" v-model="list.wholesale">
                    </td>
                @else
                    <td>
                        @{{list.wholesale}}
                    </td>
                @endif
                <td>@{{list.retail}}</td>
                <td>@{{list.warehouse_category}}</td>
                <td>@{{list.warehouse_stock}}</td>
                <td>@{{list.branch_total_stock}}</td>
                <td>
                    <a class="btn-floating btn-small" @click="update_product_info(list)">
                        <i class="large material-icons">done</i>
                    </a>
                </td>
                
            </tr>
             
          </tbody>
       
        
    </table>
</div>


@push('detail_product_warehouse')
<script>
    var sortOrder = false;
    var pussy = new Vue({
        el:'#pussy',
        data:{
            data:[],
            search:'',
            howmany:'',
            order:true
        },

        created:function(){
            this.loading = true;
            axios({
                method:'get',
                url:api_endpoint+'detail_product_warehouse_basic_info'
            }).then((res)=>{
                res.data.list.forEach((e)=>{
                    e.searchStr = e.name.toString().toLowerCase().concat(e.reference.toString().toLowerCase());
                })
                pussy.data = res.data.list;
                this.howmany = res.data.how_many
                console.log(this.data)
            })
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
		},
       
        methods:{
            choose_by_category:function(){
                let cata_id = Number($('.chooseshops :selected').val());
                if(Number(cata_id) == 0){
                    alert('Select a valid category first, are you stupid?')
                }else{
                        axios({
                        methods:'get',
                        url:api_endpoint+'detail_product_warehouse_basic_info/'+cata_id
                    }).then((res)=>{
                        res.data.list.forEach((e)=>{
                            e.searchStr = e.name.toString().toLowerCase().concat(e.reference.toString().toLowerCase());
                        })
                        pussy.data = res.data.list
                        this.howmany = res.data.how_many
                    })
                }
            },
            update_product_info:function(list){               
                if(list.cost >= 0 && list.wholesale >= 0 ){
                    axios({
                        method:'put',
                        data:{
                            supplier:list.supplier,
                            cost:list.cost,
                            wholesale:list.wholesale,
                            id:list.id
                        },
                        url:api_endpoint+'detail_product_warehouse_basic_info'
                    }).then((e)=>{
                        console.log(e.data)
                        if(e.data == 'success'){
                            alert('updated successfully')
                        }else{
                            alert('updated failed!!!')
                        }
                    })
                    
                }else{
                    alert('price can not be less than 0')
                }
            },
            sortorder:function(prop){
                this.order = !this.order 
                this.data.sort((a,b)=>this.order ? a[prop] - b[prop]: b[prop] - a[prop]);
            }
            
        },
        
    })
</script>
@endpush
@stop
@else
    <h3>Please Loggin</h3>
@endif
