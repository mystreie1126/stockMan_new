@extends('template')

@section('content')
@if(Auth::check())

<div class="container">
    <input type="hidden" value="{{Auth::User()->id}}" class="staffID">
    <div class="all_devices">
        <div class="row" style="margin-top:20px">
            <select class="col s4 assignShop">
                    <option disabled selected><span class="red-text center">Target Branch</span></option>
                    @foreach($shops as $shop)
                    <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                    @endforeach
            </select>

           <div class="col s2"></div>

           <div class="col s6" style="display:flex;align-items:center;">
               <i class="material-icons" style="transform:translateY(-20%)">search</i>
               <input type="text" v-model="search" placeholder="Search model name, IMEI, color or condition" class="searchable red-text">
           </div>


        </div>

        <div class="">
            <div v-if="filterDevice.length == 0">
               <h5 class="center">No Device Found</h5>
            </div>
            <div v-else>
              <h5 class="center">Total <span class="teal-text">@{{filterDevice.length}}</span> Device(s) found</h5>
            </div>

            <div v-if="all_devices.length > 0" v-for="(e,i) in filterDevice">
                <div class="test_1">
                    <div class="row">
                      <h5 class="col s12 m6 l6 indigo-text text-darken-4">@{{e.brand}} @{{e.model}} @{{e.storage}} @{{e.color}}</h5>
                      <h5 class="col s12 m6 l6">IMEI: <span class="amber-text">@{{e.IMEI}}</span></h5>
                      <h5 class="col s12 m6 l6">Conditon: <span class="teal-text">@{{e.condition}}</span></h5>

                      <div class="col s12">
                          <h5 class="amber-text">Notes:</h5>
                          <textarea placeholder="max 100 words..." class="transfer_notes" v-model="e.notes"></textarea>
                      </div>

                      {{-- <h5 class="col s6 m6 l6 orange-text" >@{{stock.reference}}</h5> --}}

                     <button class="col s3 btn right assignTo" @click.prevent="assignTo(e.device_id,e.notes)">Assign</button>
                   </div>
             </div>
        </div>
    </div>
</div>

@stop
@push('device_newDevice_js')
    <script type="text/javascript" src="{{URL::asset('js/device/device_in_out.js')}}"></script>
@endpush
@endif
