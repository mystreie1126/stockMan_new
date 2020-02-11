@extends('template')
@if(Auth::check())
@section('content')

@if($tasks->count()>0)
<ul class="collapsible">
@foreach($tasks as $task)
    <li>
        <div class="collapsible-header row">
            <span class="col s2">Task Number: <span class="indigo-text">{{$task->id}}</span></span>
            <span class="col s1">{{$task->shopname}}</span>
            <div class="col s2">
                <span class="red-text">{{$task->selected_from}}</span><br>
                    to<br>
                <span class="red-text">{{$task->selected_to}}</span>
            </div>
            <div class="col s2">Created at:<br>
                <span class="teal-text">{{$task->created_at}}</span>
            </div>
        <a class="btn green col s1" href="{{route('scanproducts',$task->id)}}">scan</a>
            <button class="btn red col s1 delete_task">Delete</button>  
            <input type="hidden" class="task_id" value="{{$task->id}}">
            <button class="btn amber col s1 export_task">product lists</button> 
             
        </div>
        <div class="collapsible-body">
            <table class="centered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Suggest</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($task->products as $product)
                        <tr>
                            <td>{{$product->barcode}}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->suggest}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </li>
@endforeach
</ul>
@endif


<div class="container">
        <p style="display:flex; align-items:center">
                <i class="material-icons left red-text" style="font-size:3rem;">phone_android</i>
                <span>1. Send Devcies Based On Sold:</span>
            </p>
            <span style="font-style:italic">last time device send on {{$lastTime_send_devices}}</span>
            <div style="display:flex; justify-content:space-between; align-items:center">
                <span class="red-text">From:</span>
                <input type="date" class="center select_device_sold_from" style="width:25%"> <span class="red-text">TO</span>
                <input type="date" class="center select_device_sold_to" style="width:25%">
                <button class="btn red deviceList">Device List</button>
            </div>
            <div class="device_sold_table_list row">
                <span class="col s2"></span>
            </div>

    <div class="branches_general_sales" id="getDeliveryDetailsForCrossCheck">
        
        <div class="row" style="margin-top:50px">
            
               

            <p style="display:flex; align-items:center">
                <i class="material-icons left teal-text" style="font-size:3rem;">airport_shuttle</i>
                <span>2. Weekly Delivery Based On Standard:</span>
            </p>
            <div class="input-field col s12 m3 l3" style="transform:translateY(20%)">
                <select class="teal-text" style="display:block" v-model="selectedShop" >
                    
                    @foreach($shops as $shop)
                        <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                    @endforeach
                </select>
                <label>Select a shop</label>
            </div>
            <div class="col s12 m3 l3 " class="datetime">
                <span class="indigo-text text-lighten-3">Start datetime:</span>
                <input type="datetime-local" id="selected_start_date" v-model="startTime">
            </div>
                
            <div class="col s12 m3 l3" class="datetime">
                <span class="indigo-text text-lighten-3">End datetime:</span>
                <input type="datetime-local" id="selected_end_date" v-model="endTime">
            </div>
            <button type="button" class="btn s12 m3 l3" style="transform:translateY(80%)" @click.prevent ="getList">Go</button>

            <div v-if="lists.products.length > 0" class="col s12">
                <button class="btn red delete_task" @click.prevent="create_task($event)">Create Task</button>
                <table class="centered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Suggest</th>
                            <th class="red-text">Standard</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product,index) in lists.products">
                            <td>@{{product.name}}</td>
                            <td>@{{product.ref}}</td>
                            <td>@{{product.qty}}</td>
                            <td>@{{product.standard_quantity}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>


@stop
@push('crosscheck_js')
<script>

    var deliery = new Vue({
        el:'#getDeliveryDetailsForCrossCheck',
        data:{
            selectedShop:'',
            startTime:'',
            endTime:'',
            lists:{
                products:[],
                parts:[],
                other:{
                    from:'',
                    to:'',
                    shop_id:''
                }
            }
           
        },
        methods:{
            getList:function(){       
                   
                if(this.startTime !== '' && this.endTime !== '' && this.selectedShops !== ''){
                    this.lists.other.from = this.lists.other.to = this.lists.other.shop_id = '';
                    axios({
                        method:'get',
                        url:api_endpoint+`all-replishment-lists?from=${this.startTime}&to=${this.endTime}&shop_id=${this.selectedShop}`
                        //url:api+'test'
                    }).then((res)=>{
                        if(res.data.status == 'success'){                           
                            let api_response = res.data.data
                            console.log(api_response)
                            //this.lists.parts    = api_response.lists.parts;
                            this.lists.products = api_response.lists.products.sold.concat(api_response.lists.products.standard);
                            this.lists.products.sort((a,b)=>(a.ref > b.ref)? 1: -1);
                           
                            this.lists.other.from      = api_response.other.from;
                            this.lists.other.to        = api_response.other.to;
                            this.lists.other.shop_id   = api_response.other.shop_id;
                            //console.log(api_response.lists.devices);
                           

                        }else if(res.data.status == 'failed'){
                            alert('internal error')
                        }                   
                    }).catch((err)=>{
                        console.log(err);
                    })
                }       
            },
            create_task:function(e){
                let r = confirm(`Do u wanna create the delivery task for shop_id:${this.lists.other.shop_id} and chose date between from ${this.lists.other.from} to ${this.lists.other.to}?`)
                if(r == true){
                    e.target.disabled = true
                    axios({
                        method:'post',
                        data:{
                            lists:JSON.stringify(this.lists)
                        },
                        url:api_endpoint+'replishment-task'
                    }).then((e)=>{
                        console.log(e.data);
                        // if(e.data.status == 'success'){
                        //     window.location.href = "./"
                        // }
                    })
                }        
                 
            }
        }
    });


    $('.delete_task').click(function(e){
        e.preventDefault();
        let r = confirm("Do you wanna delete this task? (suggest standard replishment quantities may changes by the time)");
        if(r == true){
            let taskId = $(this).next().val();
            console.log(taskId)
            submit_once($(this),'deleting...')
            axios({
                method:'delete',
                url:api_endpoint+'replishment-task/'+taskId
            }).then((e)=>{
                if(e.data.status == 'success'){
                    window.location.href = "./"
                }
            })
        }  
    })

    $('.export_task').click(function(e){
        e.preventDefault();
        let task_id = $(this).prev().val();
        console.log(task_id);
       
        $.ajax({
            type:'get',
            dataType:'json',
            url:api_endpoint+'replishment-task/need-to-check-products/'+task_id,
            success:function(res){
                console.log(res.data);
                var doc = new jsPDF();
                var shopname = res.data.shopname;           
                var rows = res.data.products.map(e=>[e.suggest,e.name,e.barcode,e.standard_quantity])

                doc.autoTable({ 
                    body:rows,
                    theme:'grid',
                    headStyles: {fontSize:10,cellPadding:2,cellWidth:'auto',haligin:'center',valigh:'center'},
                    columnStyles:{0:{cellWidth:20,halign:'center'},1:{cellWidth:110},2:{cellWidth:30,halign:'center'},3:{cellWidth:20,halign:'center'}},
                    columns: [{header: 'Send', dataKey: 0}, {header: 'Name', dataKey: 1},{header: 'Barcode', dataKey: 2,haligin:'center'},{header: 'Standard', dataKey: 3,haligin:'center'}],
                    didDrawPage:function(){
                        doc.text(`Job Number:${task_id} ${shopname} suggest send list`,20,10)
                    }
                });
                            
                doc.save(`${shopname} task ${task_id} suggest.pdf`)
            }
        })
    })

 
    $('.deviceList').click(function(e){
        e.preventDefault();
        let from = $('.select_device_sold_from').val(),
            to   = $('.select_device_sold_to').val() + ' 23:00:00';
        console.log(to)
        if(from !== '' && to !== '' && from < to){
            $.ajax({
                type:'get',
                url:api_endpoint+`replishment/devices_sold_lists?from=${from}&to=${to}`,
                success:function(res){
                    console.log(res.data)
                    if(res.data.devices.length == 0){
                        alert(`no devices sold during ${res.data.date_from} to ${res.data.date_to}`)
                    }else{
                        var doc = new jsPDF();
                            doc.autoTable({ 
                            body:res.data.devices,
                            theme:'grid',
                            didDrawPage:function(){
                                doc.text(`all shop device sold from ${from} to ${to}`,20,10)
                            }
                        });
                        doc.save(`devices sold ${from} to ${to}.pdf`)
                    }
                },
                error:function(error,code,text){
                    alert(error.status+' '+code+' '+text)
                }
            })
        }else{
            alert('wrong time choosen')
        }
        
    })


</script>
@endpush
@endif
