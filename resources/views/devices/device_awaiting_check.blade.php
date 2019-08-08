@extends('template')
@if(Auth::check())
@section('content')

<div class="container">
    <p class="flow-text">Check avaialble devices for testing</p>
    <div id="awaiting_check_devices">
        <input type="text" v-model="search" placeholder="Search Device ID, IMEI/Serial Number, Model etc" style="margin-top:20px">

        <div class="preloader-wrapper big active pre-loader" v-if="loading">
          <div class="spinner-layer spinner-blue-only">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>

        <ul v-if="devices.length > 0" v-for="(device,index) in filterDevices">
           <li class="collection-item row center">
               <div>
                   <span class="col s1" v-if="device.tested == 1"><i class="material-icons green-text" style="font-size:40px">check</i></span>
                   <span class="col s1" v-else><i class="material-icons red-text" style="font-size:40px">close</i></span>

                   <span class="col s1" v-if="device.pre_own == 1">Pre Owned</span>
                   <span class="col s1" v-else>Brand New</span>

                   <span class="col s2">DEVICE ID: @{{device.device_id}}</span>
                   <span class="col s3">@{{device.brand_name}} @{{device.model_name}}</span>
                   
                   <span class="col s3">@{{device.serial_number}}</span>
                   <a @click="test_device_page(device.device_id)" class="secondary-content col s2" style="display:flex; justify-content:space-between; cursor:pointer">
                       <i class="material-icons indigo-text" style="font-size:40px">phonelink_setup</i>
                   </a>
               </div>
           </li>
        </ul>
    </div>
</div>



@stop
@push('deviceTest_js')
    <script type="text/javascript" src="{{URL::asset('js/device/deviceTest.js')}}"></script>
@endpush
@endif
