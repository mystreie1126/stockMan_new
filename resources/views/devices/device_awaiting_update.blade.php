@extends('template')
@if(Auth::check())
@section('content')


        <div id="devices_waitingForUpdate">


            <ul class="collapsible" data-collapsible="accordion">

                    <li v-for="(device,index) in devices">
                      <div class="collapsible-header row">
                          <span class="col s2"><span class="orange-text text-darken-1">Device ID: </span>@{{device.id}}</span>
                          <span class="col s3" v-if="Number(device.pre_own == 1)">
                              Brand New Device
                          </span>
                          <span class="col s3" v-else>
                              Pre Own Device
                          </span>
                          <span class="blue-text text-darken-1 col s4">@{{device.brand_name}} @{{device.model_name}}</span>
                      </div>
                      <div class="collapsible-body">
                          <form class="row test-form" action="" method="post">
                              <div class="col s6">
                                  <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked" v-model="device.dead"/>
                                  @{{device.dead}}
                                  <label for="filled-in-box">Can't Turn on</label>
                              </div>
                              <div class="switch col s6">
                                  @{{device.has_imei}}
                                   <label style="color:#7e57c2">
                                       IMEI
                                       <input type="checkbox" v-model="device.has_imei">
                                       <span class="lever"></span>
                                       Serial Number
                                   </label>
                              </div>

                              <div class="input-field col s6" >
                                   <input id="last_name" type="text" class="validate">
                                   <label for="last_name">IMEI</label>
                             </div>
                             
                             <button class="btn">Save</button>
                          </form>
                      </div>
                    </li>
            </ul>

    </div>


@stop
@push('deviceTest_js')
    <script type="text/javascript" src="{{URL::asset('js/device/deviceTest.js')}}"></script>
@endpush
@endif
