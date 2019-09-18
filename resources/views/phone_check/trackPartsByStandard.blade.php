@if(Auth::check())
@extends('template')
@section('content')
<div>
	<div class="branches_general_sales" id="trackPartsByStandard">
		<div style="margin-top:10px; display:flex; justify-content:space-around">
			<div class="input-field">
                <select class="teal-text" style="display:block"  v-model="shop_id">
                    @foreach($shops as $shop)
                        <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                    @endforeach
				</select>
				<label>Select the Branch</label>
            </div>
            
            <button class="black btn" style="transform:translateY(50%)" @click.prevent="click">Track Parts By standard</button>
        </div>
        
        <table style="margin-top:15px" class="highlight centered striped bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Standard</th>
                    <th>In RockPos</th>
                    <th>Suggest Send</th>
                    <th>Repair By Standard</th>
                    <th>Send Between Repairs</th>
                    <th>Total Send</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in list">
                  <td>@{{list.name}}</td>
                  <td>@{{list.standard}}</td>
                  <td>@{{list.quantity}}</td>
                  <td>@{{list.suggest_send}}</td>
                  <td>
                      <div v-for="item in list.repair_historyByStandard">
                            <span class="teal-text">Date:</span>@{{item.date_add}}<br>
                            <span class="red-text">Ref:</span>@{{item.reference}}<br>
                            <span class="indigo-text">Track Number:</span>@{{item.message}}<br>
                      </div>
                  </td>
                  <td>@{{list.sendBetweenRepairs}}</td>
                  <td>@{{list.totalSendFromBeginning}}</td>
                </tr>
              </tbody>
        </table>
	</div>
</div>
@endif
@stop
@push('trackPartsByStandard')
    <script type="text/javascript">
        var trackPartsByStandard = new Vue({
            el:'#trackPartsByStandard',
            data:{
                shop_id:'',
                list:[]
            },
            methods:{
                click:function(){
                    console.log(this.shop_id);
                    axios({
                        method:'post',
                        data:{
                            shop_id:this.shop_id
                        },
                        url:stockMan+'checkPartsByStandard'
                    }).then((e)=>{
                        this.list = e.data;
                        console.log(this.list)
                    })
                }
            }
        })
    </script>
@endpush