@extends('template')
@section('content')
@if(Auth::check())

@stop
@push('inventory')
  <script type="text/javascript" src="{{URL::asset('js/inventory.vue.js')}}"></script>
@endpush
@else
    <h3>Please Loggin</h3>
@endif
