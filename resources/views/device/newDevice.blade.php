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
                {{-- <div class="turn_on bold teal-text">Power:</div>
                 <label>
                   <span class="bold">OFF</span>
                   <input type="checkbox" v-model="power" @change="test" >
                   <span class="lever"></span>
                   <span class="bold">On</span>
                 </label> --}}
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


    {{-- <div class="second-line device_details_line row">

       <div class="col s3" :style="[main.wifi === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.wifi == true ? {color:'green'}:{color:'red'}]">WIFI:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.wifi" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

        <div class="col s3" :style="[main.frontCamera === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.frontCamera == true ? {color:'green'}:{color:'red'}]">Front Camera:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.frontCamera" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


        <div class="col s3" :style="[main.proximity === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.proximity == true ? {color:'green'}:{color:'red'}]">Proximity:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.proximity" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.cameraFlash === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.cameraFlash == true ? {color:'green'}:{color:'red'}]">Camera Flash:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.cameraFlash" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.rearCamera === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.rearCamera == true ? {color:'green'}:{color:'red'}]">Rear Camera:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.rearCamera" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.earSpeaker === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.earSpeaker == true ? {color:'green'}:{color:'red'}]">Ear Speaker:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.earSpeaker" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


    </div>

    <div class="third-line device_details_line row">

        <div class="col s3" :style="[main.vibration === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.vibration == true ? {color:'green'}:{color:'red'}]">Vibration:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.vibration" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.muteButton === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.muteButton == true ? {color:'green'}:{color:'red'}]">Mute Button:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.muteButton" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.volumeButton === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.volumeButton == true ? {color:'green'}:{color:'red'}]">Volume Button:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.volumeButton" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


        <div class="col s3" :style="[main.gps === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.gps == true ? {color:'green'}:{color:'red'}]">GPS:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.gps" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.bluetooth === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.bluetooth == true ? {color:'green'}:{color:'red'}]">Bluetooth:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.bluetooth" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


        <div class="col s3" :style="[main.magnetometer === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.magnetometer == true ? {color:'green'}:{color:'red'}]">Magnetometer:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.magnetometer" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


        <div class="col s3" :style="[main.accelerometer === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.accelerometer == true ? {color:'green'}:{color:'red'}]">Accelerometer:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.accelerometer" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>


        <div class="col s3" :style="[main.call === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.call == true ? {color:'green'}:{color:'red'}]">Call:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.call" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

        <div class="col s3" :style="[main.appearance === ''?{background:'#e0fff6'}:{background:'none'}]">
          <div class="turn_on bold" :style="[main.appearance == true ? {color:'green'}:{color:'red'}]">Appearance:</div>
          <div class="switch">
            <label>
              No
              <input type="checkbox" v-model="main.appearance" :disabled="isDisabled">
              <span class="lever"></span>
              Yes
            </label>
          </div>
        </div>

    </div>


    <div class="forth-line device_details_line row">
       <div class="col s3" :style="[main.earSpeaker === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.earSpeaker == true ? {color:'green'}:{color:'red'}]">Ear Speaker:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.earSpeaker" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

       <div class="col s3" :style="[main.powerButton === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.powerButton == true ? {color:'green'}:{color:'red'}]">Power Button:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.powerButton" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

       <div class="col s3" :style="[main.simTray === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.simTray == true ? {color:'green'}:{color:'red'}]">SIM Tray:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.simTray" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>


       <div class="col s3" :style="[main.lcd === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.lcd == true ? {color:'green'}:{color:'red'}]">LCD:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.lcd" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

       <div class="col s3" :style="[main.touchScreen === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.touchScreen == true ? {color:'green'}:{color:'red'}]">TouchScreen:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.touchScreen" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

       <div class="col s3" :style="[main.touchID === ''?{background:'#e0fff6'}:{background:'none'}]">
         <div class="turn_on bold" :style="[main.touchID == true ? {color:'green'}:{color:'red'}]">Touch ID:</div>
         <div class="switch">
           <label>
             No
             <input type="checkbox" v-model="main.touchID" :disabled="isDisabled">
             <span class="lever"></span>
             Yes
           </label>
         </div>
       </div>

    </div>

    <div class="fifth-line device_details_line row">

      <div class="col s3" :style="[main.earphone === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.earphone == true ? {color:'green'}:{color:'red'}]">Earphone:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.earphone" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.microphone === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.microphone == true ? {color:'green'}:{color:'red'}]">Microphone:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.microphone" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.charging === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.charging == true ? {color:'green'}:{color:'red'}]">Device Charging:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.charging" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.homeButton === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.homeButton == true ? {color:'green'}:{color:'red'}]">Home Button:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.homeButton" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.loudSpeaker === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.loudSpeaker == true ? {color:'green'}:{color:'red'}]">Loud Speaker:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.loudSpeaker" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>


    </div>

    <div class="sixth-line device_details_line row">

      <div class="col s3" :style="[main.chargeBattery === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.chargeBattery == true ? {color:'green'}:{color:'red'}]">Charge Battery to 100%:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.chargeBattery" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.batteryLife === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.batteryLife == true ? {color:'green'}:{color:'red'}]">Battery life test:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.batteryLife" :disabled="isDisabled">
            <span class="lever"></span>
            Yes
          </label>
        </div>
      </div>


      <div class="col s3" :style="[main.innerDust === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.innerDust == true ? {color:'green'}:{color:'red'}]">Clean inner dust:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.innerDust" :disabled="isDisabled">
            <span class="lever"></span>

          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.turnOffDevice === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.turnOffDevice == true ? {color:'green'}:{color:'red'}]">Turn Off the Device:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.turnOffDevice" :disabled="isDisabled">
            <span class="lever"></span>

          </label>
        </div>
      </div>

      <div class="col s3" :style="[main.updateLatest === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.updateLatest == true ? {color:'green'}:{color:'red'}]">Update to the latest Version:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.updateLatest" :disabled="isDisabled">
            <span class="lever"></span>

          </label>
        </div>
      </div>

      <div class="col s9" :style="[main.warrantySticker === ''?{background:'#e0fff6'}:{background:'none'}]">
        <div class="turn_on bold" :style="[main.warrantySticker == true ? {color:'green'}:{color:'red'}]">Funtech Warrantly Sticker on Screen Connectior Panel and Battery:</div>
        <div class="switch">
          <label>
            No
            <input type="checkbox" v-model="main.warrantySticker" :disabled="isDisabled">
            <span class="lever"></span>

          </label>
        </div>
      </div>

    </div> --}}

        <button class="btn-large right blue saveTo" @click.prevent="saveTo()">Save this Deivce</button>

    </div>

</div>
@stop
@push('device_newDevice_js')
    <script type="text/javascript" src="{{URL::asset('js/device/device_in_out.js')}}"></script>
@endpush
@endif
