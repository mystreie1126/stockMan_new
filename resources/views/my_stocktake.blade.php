@extends('template')

@section('content')
@if(Auth::check())
    <div class="container">
        <input type="hidden" value="{{Auth::User()->id}}">
        <div class="mystocktake_filter" style="display:flex; justify-content:space-between; align-items:center">
            <input type="text" class="mystocktake_filter_ref" placeholder="Search Reference">
            <p>OR</p>
            <input type="text" class="mystocktake_filter_name" placeholder="Search Name">
        </div>
        <div class="mystocktake_table">
        </div>
    </div>
@endif
@stop
@push('mystocktake_js')
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.7/dist/js/tabulator.min.js"></script>
    <script type="text/javascript" src="{{URL::asset('js/mystocktake.js')}}"></script>
@endpush
