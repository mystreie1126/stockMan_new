@if(Auth::check())
@extends('template')
@section('content')

<div class="container" id="technical_test">
    <input type="show" value="{{Auth::User()->id}}" class="user_id">
    <input type="show" value="{{$device_id}}" id="technical_device_id">
    <div v-if="issues.length > 0" class="row">
        <div class="col s12">
            Can't turn on?
            <div class="switch">
                <label>
                  No
                  <input type="checkbox" @change="isTurnOn(cant_turn_on.checked)" v-model="cant_turn_on.checked">
                  <span class="lever"></span>
                  Yes
                </label>
            </div>
        </div>
        <div v-for="(issue,index) in issues" class="col s4">
            @{{issue.description}}
            <div class="switch">
                <label>
                  Faulty
                  <input type="checkbox" v-model="issue.checked" :disabled="isDisabled">
                  <span class="lever"></span>
                  Working
                </label>
            </div>
        </div>


    </div>
    <button class="btn" @click.prevent="submit_issues" style="margin-bottom:20px">Submit</button>
</div>

@stop
@push('deviceTest_js')
    <script src="{{URL::asset('js/device/deviceTest.js')}}"></script>
@endpush
@endif
