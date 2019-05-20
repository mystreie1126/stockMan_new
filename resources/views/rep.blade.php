@extends('template')

@section('content')



@if(Auth::check())

<div id="replishmentLists" style="margin:20px">

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
          <span class="flow-text  cyan-text text-darken-3 ">Regular Re-Stock</span>
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

        <div class="regular_list_action hide row">
            <div class="col s12" style="display:flex">
              <p class="bold"></p>
              <a class='dropdown-button btn right' style="transform:translate(20%,20%)" data-activates='dropdown1'>Download</a>
               <ul id='dropdown1' class='dropdown-content'>
                 <li><a class="downloadCSV">CSV</a></li>
                 <li><a id="downloadExcel">EXCEL </a></li>
               </ul>
           </div>
          <input type="text" value="" placeholder="Search by name" id="filter-name" class="col s4">
          <span class="col s1 center" style="transform:translateY(40%)">OR</span>
          <input type="text" value="" placeholder="search by reference" id="filter-barcode" class="col s4">

          <button type="button" class="blue btn col s2" id="regular_list_submit" style="transform:translate(10%,10%)">Submit</button>
        </div>

        <div id="regular_list"></div>
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

</div>



@endif
@stop

@push('replishment_js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.2/jspdf.plugin.autotable.js"></script>
    <script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.7/dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
@endpush
