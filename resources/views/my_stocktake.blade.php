@extends('template')

@section('content')
@if(Auth::check())
    <div class="container" id="mystockTake">
        <input type="hidden" value="{{Auth::User()->id}}" class="stockTake_user">
        <h5 class="table_message"></h5>
        <div class="mystocktake_filter row">
            <input type="text" class="mystocktake_filter_ref col s4" placeholder="Search Product Reference">
            <p class="col s1 center">OR</p>
            <input type="text" class="mystocktake_filter_name col s4" placeholder="Search Product Name">

            <a class='dropdown-button btn col s2 right' href='#' data-activates='dropdown1' style="transform:translate(-10%,30%)">Options</a>
                 <ul id='dropdown1' class='dropdown-content'>
                   <li><a @click.prevent="myStockTake_records">My StockTake</a></li>
                   <li><a @click.prevent="allStockTake_records">All User StockTake</a></li>
                   <li><a @click.prevent="stockTake_final_results">Final StockTake</a></li>
                 </ul>
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
