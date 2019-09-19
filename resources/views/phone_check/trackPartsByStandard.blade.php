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

  <div class="progress hide">
      <div class="indeterminate"></div>
  </div>

		<div class="row center" style="margin-top:20px" v-if="list.length > 0">
			<span class="col s2">Name</span>
			<span class="col s1">Rockpos </span>
			<span class="col s1">Standard </span>
			<span class="col s2">Suggest Send</span>
			<span class="col s2">Parts Scanned</span>
			<span class="col s2">Send During Repairs</span>
			<span class="col s2">Total Send from Start</span>
		</div>
		<ul class="collapsible" data-collapsible="accordion">
		    <li v-for="(list,index) in list">
		      <div class="collapsible-header row center" v-bind:style="[list.repair_historyByStandard.length > 0 && list.repair_historyByStandard.length - list.suggest_send !== 0 ? {color:'red'}:{color:'black'}]">
				  {{-- <i class="material-icons col s1">build</i> --}}
				  <span class="col s2">@{{list.name}}</span>
				  <span class="col s1">@{{list.quantity}}</span>
				  <span class="col s1">@{{list.standard}}</span>
				  <span class="col s2">@{{list.suggest_send}}</span>
				  <span class="col s2">@{{list.repair_historyByStandard.length}}</span>
				  <span class="col s2">@{{list.sendBetweenRepairs}}</span>
				  <span class="col s2">@{{list.totalSendFromBeginning}}</span>
			  </div>
		      <div class="collapsible-body" v-for="(item,ind) in list.repair_historyByStandard">
					<p class="container row">
						<span class="col s3  deep-purple-text text-darken-3">@{{item.fullname}}</span>
						<span class="col s3">@{{item.date_add}}</span>
						<span class="col s3">@{{item.reference}}</span>
						<span class="indigo-text col s3">
							@{{item.message}}
						</span>
					</p>
			  </div>
		    </li>

	  	</ul>
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
					removeHide($('.progress'));
                    axios({
                        method:'post',
                        data:{
                            shop_id:this.shop_id
                        },
                        url:stockMan+'checkPartsByStandard'
                    }).then((e)=>{
                        this.list = e.data;
                        console.log(this.list)
						addHide($('.progress'));
                    })
                }
            }
        })
    </script>
@endpush
