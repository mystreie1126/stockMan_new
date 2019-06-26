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
                 <input id="supplier_order" type="text" class="validate">
                 <label for="supplier_order">Supplier Order ID</label>
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




      </div>


        <button class="btn-large right blue saveTo">Save this Deivce</button>

    </div>

</div>
@stop
@push('device_newDevice_js')
@endpush
@endif
