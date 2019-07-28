@extends('template')
@if(Auth::check())
@section('content')

<div class="container">


<ul class="collection with-header">
    @if(count($awaiting_devices) > 0)
        <li class="collection-header"><h4>First Names</h4></li>
        @foreach($awaiting_devices as $device)
            <li class="collection-item">
                <div class="row">
                    <span class="col s3"><i class="material-icons" style="transform:translate(-20%,20%)">phone_android</i>
{{$device->brand_name.' '.$device->model_name}}</span>
                    <span class="col s2">
                        @if($device->pre_own == 1)
                            Brand New
                        @else
                            Pre Owned
                        @endif
                    </span>
                    <a href="#!" class="col s4"><i class="material-icons">send</i></a>
                </div>
            </li>
        @endforeach
    @else
    <p class="flow-text">Don't have any Devices need to test</p>
@endif
</ul>

</div>



@stop
{{-- @push('deviceTest_js')
    <script type="text/javascript" src="{{URL::asset('js/device/deviceTest.js')}}"></script>
@endpush --}}
@endif
