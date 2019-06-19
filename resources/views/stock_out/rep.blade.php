@extends('template')

@section('content')



@if(Auth::check())

<div id="replishmentLists" style="margin:20px">

<div class="preloader-wrapper big active pre-loader hide">
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

@if($need_upload->count() > 0)
    <div class="fixed-action-btn horizontal">
        <a class="btn btn-floating pulse red"><i class="material-icons">priority_high</i></a>
        <ul>
          <li><a class="red-text">There are remaining data needs to be uploaded</a></li>
        </ul>
    </div>

@endif

  <h3></h3>
  <h5>Re Stock Options:</h5>
<ul class="collapsible" data-collapsible="accordion">

    <li>
      <div class="collapsible-header"><i class="material-icons green-text">whatshot</i>Standard Restock</div>
      <div class="collapsible-body">

        <div class="row" >
           <div class="col s12 m4 l4">
            <span class="green-text text-lighten-3">Select Branch:</span>
            <select id="selected_standard_stock_shop">
              <option disabled selected>Choose a Shop</option>
                  <option value="27">Athlone</option>
                  <option value="26">Mill</option>
            </select>
          </div>

          <button class="green btn col s6 m2 l2" id="createStandardList" style="transform:translate(10%,80%)">Create List</button>
      </div>
    </li>




<li>
  <div class="collapsible-header"><i class="material-icons teal-text">filter_drama</i>Re-stock by Sales</div>
  <div class="collapsible-body">

    <div class="sales_rep_form">
      <span class="flow-text  cyan-text text-darken-3 ">Re-Stock by Sales</span>
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
             <li><a id="downloadExcel">EXCEL </a></li>
           </ul>
       </div>
      <input type="text" value="" placeholder="Search by name" id="filter-name" class="col s4">
      <span class="col s1 center" style="transform:translateY(40%)">OR</span>
      <input type="text" value="" placeholder="search by reference" id="filter-barcode" class="col s4">

      <button type="button" class="blue btn col s2" id="regular_list_submit" style="transform:translate(10%,10%)">Submit</button>
    </div>

  </div>
</li>


    {{-- custom restock --}}
    <li>
      <div class="collapsible-header"><i class="material-icons">whatshot</i>Custom Restock (Any Time)</div>
      <div class="collapsible-body" id="custom_rep">

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
            <input type="text" v-model="search">
            <label for="custom_stock_ref">Reference or Name</label>
        </div>
        <button class="btn s12 m3 l3 blue custom_stock_search" @click.prevent="ajax_getStock" style="transform:translateY(80%)">Search</button>
        <div class="col s12">
            <table class="centered striped">
                <thead>
                    <tr>
                        <th @click="sortName">Name</th>
                        <th @click="sortRef">Reference</th>
                        <th @click="sortSend_qty">Send</th>
                        <th @click="sortShop">Shop</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in custom_lists">
                        <td>@{{list.name}}</td>
                        <td>@{{list.ref}}</td>
                        <td>
                            <input type="number" v-model="list.send" style="width:auto" class="center indigo-text">
                        </td>
                        <td>@{{list.shopname}}</td>
                        <td>
                            <button class="btn red" @click.prevent="deleteThis(index)">Delete</button>
                        </td>
                    </tr>
                </tbody>
                <button class="btn blue save_custom_rep" @click.prevent="submitThis">Submit</button>
            </table>
        </div>
        </div>
    </div>
    </li>

  </ul>
  {{-- end of the whole list action form--}}
  <hr>

  <div class="download"></div>
  <div class="message"></div>

  {{-- data list  --}}
  <h5>Replishment List:</h5>
  <div id="replishment_list"></div>
  <div class="list_action"></div>

</div>



@endif
@stop

@push('replishment_js')
<script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
@endpush
