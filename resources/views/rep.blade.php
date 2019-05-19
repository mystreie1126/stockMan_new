@extends('template')

@section('content')



@if(Auth::check())

<div class="container" id="replishmentLists">

<div class="preloader-wrapper big active pre-loader" v-if="list_loading">
  <div class="spinner-layer spinner-red-only">
    <div class="circle-clipper left">
      <div class="circle"></div>
    </div><div class="gap-patch">
      <div class="circle"></div>
    </div><div class="circle-clipper right">
      <div class="circle"></div>
    </div>
  </div>
</div>

  <h3></h3>
  <h5>Re Stock Options:</h5>
  <ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header"><i class="material-icons">filter_drama</i>Regular Re-stock (Monday and Thursday)</div>
      <div class="collapsible-body">

        <div class="sales_rep_form">
          <p class="flow-text  cyan-text text-darken-3 ">Regular Re-Stock</p>
          <div id="sales_rep_form" class="row" >
             <div class="col s12 m3 l3">
              <span class="indigo-text text-lighten-3">Select From:</span>
              <select id="selected_shop">
                <option disabled selected>Choose a Shop</option>
                  @foreach($shops as $shop)
                    <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                  @endforeach
              </select>
              <label>Select Branches</label>
            </div>
          <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">Start datetime:</span>
               <input type="date" id="selected_start_date">
          </div>
          <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">End datetime:</span>
               <input type="date" id="selected_end_date">
          </div>
          <button type="button" v-on:click.prevent="getList" class="btn s12 m3 l3" id="createSalesList" style="transform:translateY(80%)">Create List</button>
          </div>
        </div>


      </div>
    </li>


    <li>
      <div class="collapsible-header"><i class="material-icons">whatshot</i>Custom Restock (Any Time)</div>
      <div class="collapsible-body">

        <div class="row" >
           <div class="col s12 m3 l3">
            <span class="indigo-text text-lighten-3">Select Branch:</span>
            <select id="selected_custom_stock_shop">
              <option disabled selected>Choose a Shop</option>
                @foreach($shops as $shop)
                  <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                @endforeach
            </select>
            <label>Select Branches</label>
          </div>
        <div class="col s12 m6 l6 input-field" style="transform:translateY(10%)">
            <input id="custom_stock_ref" type="text" >
            <label for="custom_stock_ref">Reference</label>
        </div>

        <button type="button" class="btn s12 m3 l3 blue" id="count_search" style="transform:translateY(80%)">search</button>
        </div>


      </div>
    </li>
  </ul>


















  {{-- end of sales replishment form--}}


  <div class="regular_list_action hide">
    <input type="text" value="" placeholder="Search by name" id="filter-name">
    {{-- <span>OR</span> --}}
    {{-- <input type="text" value="" placeholder="search by reference" id="filter-barcode"> --}}
  </div>
  <div id="regular_list" style="margin-bottom:20px"></div>





</div>




@endif
@stop

@push('replishment_js')
  <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.7/dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
@endpush
