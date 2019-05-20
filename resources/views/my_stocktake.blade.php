@extends('template')

@section('content')
@if(Auth::check())
    <div class="container" id="mystocktake">
        <input type="hidden" value="{{Auth::User()->id}}">

        <div class="mystocktake_table">
        </div>
    </div>
@endif
@stop
@push('mystocktake_js')
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.7/dist/js/tabulator.min.js"></script>
    <script type="text/javascript" src="{{URL::asset('js/mystocktake.js')}}"></script>
@endpush
