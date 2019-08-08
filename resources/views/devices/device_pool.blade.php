@if(Auth::check())
@extends('template')
@section('content')

<h5 class="center">New Device Stock In</h3>
<p class="center">Staff: {{Auth::User()->name}}</p>
<input type="hidden" value="{{Auth::User()->id}}" class="staffID">
<div class="container">
    <div id="device_pool_stockIn" class="row">
        <div class="col s6" >

            <span class="flexx">
                <i class="material-icons">adjust</i><span>&nbsp;Choose Device condition:</span>
            </span>
            <div style="margin-top:10px">
                <input type="checkbox" id="test5" value="true" v-model="brandNew"/>
                <label for="test5">@{{condition}}</label>
            </div>
        </div>

        <div class="col s6">
            <span class="flexx">
                <i class="material-icons">assignment</i><span>&nbsp;Supplier Order ID:</span>
            </span>
            <input type="text" class="validate" v-model="basic.supplier_order_id">
        </div>




        <div class="mobile_device_condition col s6">

            <div class="teal-text text-darken-4" v-if="brandNew">
                Available brands:
                <select style="display:block" v-model="jiance.brandnew_brand_category_id" class="teal-text brand_new_select">
                     <option disabled selected>Available Brand New Brands.....</option>
                     @foreach($brandnew_models as $brandnew_model)
                         <option value="{{$brandnew_model->id_category}}">{{$brandnew_model->name}}</option>
                     @endforeach
               </select>
               <button class="btn teal create_brandnew" style="margin-top:10px" @click.prevent="create_brandnew">Save Brand New</button>
            </div>

            <div class="indigo-text text-accent-2" v-else>
                 Available brands:
                <select style="display:block" v-model="jiance.preown_brand_category_id" class="indigo-text pre_own_select">
                    <option disabled selected>Available Pre Own Brands.......</option>
                    @foreach($preown_models as $preown_model)
                        <option value="{{$preown_model->id_category}}">{{$preown_model->name}}</option>
                    @endforeach
               </select>
               <button class="btn indigo create_preown" style="margin-top:10px" @click.prevent="create_preown">Save Pre Own</button>
            </div>

        </div>

        <div class="col s6">
            <span class="flexx">
                <i class="material-icons">android</i><span>&nbsp;Model Name:</span>
            </span>

            <input id="supplier_order" type="text" class="validate" v-model="jiance.device_model_name" required>
        </div>


    </div>

    {{-- @if(count($awaitings)>0)
        <div class="">
            <h5 class="center">Devices Awaiting Technician Tests</h5>
                @foreach($awaitings as $awaiting)
                    <li>There is(are)
                        <span class="amber-text">{{$awaiting['number']}} </span>
                        <span class="indigo-text">{{$awaiting['condition']}}</span> -
                        <span class="cyan-text">{{$awaiting['brandname']}}</span>
                        Brand devices are waiting for tested
                    </li>
                @endforeach
        </div>
    @endif --}}


    {{-- {{$types}} --}}

</div>


@stop
@push('device_pool_js')
    <script src="{{URL::asset('js/device/device_pool.js')}}"></script>
@endpush
@endif
