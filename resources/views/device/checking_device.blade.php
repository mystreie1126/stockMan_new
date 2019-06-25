@extends('template')
@section('content')
@if(Auth::check())
    <div class="container row">

        <div class="input-field col s12">
            <select>
                <option value="" disabled selected>Select a Device ID to Start</option>
                    @foreach($devices as $device)
                        <option value="{{$device->id}}">
                            {{$device->type->description}} ID: {{$device->id}} 
                        </option>
                    @endforeach
            </select>
            <label>Available Device IDs:</label>
        </div>


        <ul id="checking_device_step" class="tabs">
           <li class="tab col s6"><a href="#check_step-1" class="indigo-text indigo-darken-4">Basic Info</a></li>
           <li class="tab col s6"><a href="#check_step-2" class="teal-text text-darken-4">Testing Device</a></li>
         </ul>
         <div id="check_step-1" class="col s12 red lighten-4">



         </div>
         <div id="check_step-2" class="col s12 red">Test 2</div>

    </div>



@stop

    {{-- <script type="text/javascript" src="{{URL::asset('js/device/device_in_out.js')}}"></script> --}}

@endif
