@extends('template')
@if(Auth::check())
@section('content')

<div class="container">


<h4 class="center">Stock In NEW DEVICE</h4>
<div id="newDevice">
<input type="hidden" value="{{Auth::User()->id}}" class="staffID">
        {{-- first row.... --}}


    <div class="first-line device_details_line row">
        <h5 class="indigo-text right">Staff: <span class="red-text">{{Auth::User()->name}}</span></h5>
              <div class="switch col s12" style="margin-bottom:20px">

             </div>

             <div class="input-field col s6">
                 <input id="IMEI" type="text" class="validate" v-model="basic.imei">
                 <label for="IMEi" class="red-text">Device IMEI (must have):</label>
              </div>

              <div class="input-field col s3">
                    <select class="device_storage">
                      <option disabled selected>Choose Storage</option>
                      <option value="256GB">256GB</option>
                      <option value="128GB">128GB</option>
                      <option value="64GB">64GB</option>
                      <option value="32GB">32GB</option>
                      <option value="16GB">16GB</option>
                      <option value="8GB">8GB</option>
                      <option value="4GB">4GB</option>
                      <option value="2GB">2GB</option>
                    </select>
                    <label>Select Storage:</label>
              </div>


              <div class="input-field col s3">
                  <input id="color" type="text" class="validate" placeholder="eg.space grey/red/blue" v-model="basic.color">
                  <label for="color">Color:</label>
            </div>

             <div class="input-field col s3">
                 <select class="device_brands">
                       <option disabled selected>Available Brands</option>
                       <option>Apple</option>
                       <option>Samsung</option>
                       <option>Huawei</option>
                       <option>Sony</option>
                       <option>XiaoMi</option>
                       <option>Nokia</option>
                       <option>HTC</option>
                       <option>Alcatel</option>
                       <option>Motorola</option>
                       <option>Vodafone</option>
                       <option>LG</option>
                       <option>MISC</option>
                 </select>
                 <label>Select Brand:</label>
             </div>

             <div class="input-field col s3">
                 <input placeholder="Device Model Name" id="device_model" type="text" class="validate" v-model="basic.model">
                 <label for="device_model">Model Name:</label>
             </div>

             <div class="input-field col s3">
                 <select class="device_condition">
                       <option disabled selected>Choose Condition</option>
                       <option>NEW</option>
                       <option>Grade A</option>
                       <option>Grade B</option>
                       <option>Grade C</option>
                       <option>Grade D</option>
                 </select>
                 <label>Select Brand:</label>
             </div>

            <div class="input-field col s3">
                <input placeholder="Device Order ID" id="device_orderID" type="text" class="validate" v-model="basic.order_id">
                <label for="device_orderID">Order ID (can be empty):</label>
            </div>
      </div>


        <button class="btn-large right blue saveTo" @click.prevent="saveTo()">Save this Deivce</button>

    </div>

</div>
@stop
@push('device_newDevice_js')
    <script type="text/javascript" src="{{URL::asset('js/device/device_in_out.js')}}"></script>
@endpush
@endif
