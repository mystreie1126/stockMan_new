@extends('template')

@section('content')



@if(Auth::check())

<div id="stockTracking_options" style="margin:20px">

<div class="lds-spinner spinner-loader hide"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

  <h3></h3>
  <h5>Stock Tracking Options:</h5>
<ul class="collapsible" data-collapsible="accordion">

    <li>
      <div class="collapsible-header">
          <i class="material-icons green-text">whatshot</i>Top Selling by Time
      </div>
      <div class="collapsible-body" id="standard_rep">

        <div class="row" >
            <div class="col s12 m3 l3" class="datetime">
                <span class="indigo-text text-lighten-3">Start datetime:</span>
                <input type="date" id="selected_start_date">
            </div>
            <div class="col s12 m3 l3" class="datetime">
                <span class="indigo-text text-lighten-3">End datetime:</span>
                <input type="date" id="selected_end_date">
            </div>

          <button class="green btn col s6 m2 l2" @click.prevent="get_standard_list" style="transform:translate(10%,80%)">Go!</button>

          <div class="list_showcase">
               <table class="centered striped">
                   <thead>
                       <tr>
                           <th>Product Name</th>
                           <th>Reference</th>
                           <th>standard</th>
                           <th>send</th>
                           <th class="teal-text">Retail Price</th>
                           <th class="cyan-text">Wholesale Price</th>
                           <th class="teal-text">Total Retail</th>
                           <th class="cyan-text">Total Wholesale</th>
                       </tr>
                   </thead>
                   <tbody>
                       {{-- <tr v-for="(list,index) in standard_list">
                           <td>@{{list.name}}</td>
                           <td>@{{list.reference}}</td>
                           <td>@{{list.standard}}</td>
                           <td>
                              <input type="number" v-model="list.send" style="width:40%" class="center indigo-text">
                           </td>
                           <td class="teal-text">@{{list.retail_price}} &euro;</td>
                           <th class="cyan-text center">@{{list.wholesale}} &euro;</th>
                           <td class="teal-text text-darken-2">
                               <input type="number" v-model="list.send * list.retail_price" style="width:40%" class="center green-text" disabled>
                           </td>
                           <td class="cyan-text text-accent-3">
                               <input type="number" v-model="list.send * list.wholesale" style="width:40%" class="center teal-text" disabled>
                           </td>
                       </tr> --}}
                   </tbody>
                   <button class="btn amber right" v-if="showbtn" @click.prevent="exportList">Export to CSV</button>
               </table>
          </div>
          <button class="btn blue submitStandardList" v-if="showbtn" @click.prevent="submitStandardList">Submit</button>
      </div>
    </li>




<li>
  <div class="collapsible-header"><i class="material-icons teal-text">filter_drama</i>Track by Manufactors</div>
  <div class="collapsible-body" id="sale_rep">

    <div id="sales_rep_form">
      <div class="row" >
              <div class="col s12 m3 l3">
                  <span class="indigo-text text-lighten-3">Select Manufactor:</span>
                  <select id="selected_shop">
                    <option disabled selected>Choose a Manufactor</option>
                      {{-- @foreach($shops as $shop)
                        <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                      @endforeach --}}
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
                <button type="button" v-on:click.prevent="getsalesList" class="btn s12 m3 l3" id="createSalesList" style="transform:translateY(80%)">Create List</button>
      </div>

      <div class="list_showcase">
          <table class="centered striped">
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Reference</th>
                      <th class="indigo-text">Stock In</th>
                      <th class="teal-text">Total Send</th>
                      <th class="cyan-text">Total Sold</th>
                      <th class="cyan-text">Retail</th>
                      <th class="cyan-text">Total Retail</th>
                      <th class="teal-text">Wholesale</th>
                      <th class="teal-text">Total Wholesale</th>

                  </tr>
              </thead>
              <tbody>
                  {{-- <tr v-for="(list,index) in sales_list">
                      <td>@{{list.name}}</td>
                      <td>@{{list.reference}}</td>
                      <td>
                          <input type="number" v-model="list.suggest_send" style="width:50%" class="center indigo-text">
                      </td>
                      <td class="teal-text">@{{list.retail_price}} &euro;</td>
                      <td class="cyan-text">@{{list.wholesale}} &euro;</td>
                      <td class="teal-text text-darken-2">
                          <input type="number" v-model="list.suggest_send * list.retail_price" style="width:40%" class="center amber-text" disabled>
                      </td>
                      <td class="cyan-text text-accent-3">
                          <input type="number" v-model="list.suggest_send * list.wholesale" style="width:40%" class="center teal-text" disabled>
                      </td>
                      <td>@{{list.shop_name}}</td> --}}
                  </tr>
              </tbody>
              <button class="btn amber exportSalesBtn" v-if="showbtn" @click.prevent="exportList">Export to CSV</button>
          </table>
      </div>
    </div>


  </div>
</li>


    {{-- custom restock --}}
    <li>
      <div class="collapsible-header"><i class="material-icons">whatshot</i>Tracking Single Product</div>
      <div class="collapsible-body" id="custom_rep">

        <div class="row" >

            <div class="col s12 m3 l3 input-field" style="transform:translateY(10%)">
                <input type="text" v-model="search">
                <label for="custom_stock_ref">Reference or Name</label>
            </div>

            <div class="col s12 m3 l3" class="datetime">
                <span class="indigo-text text-lighten-3">Start datetime:</span>
                <input type="date" id="selected_start_date">
            </div>
            <div class="col s12 m3 l3" class="datetime">
                <span class="indigo-text text-lighten-3">End datetime:</span>
                <input type="date" id="selected_end_date">
            </div>

        <button class="btn s12 m3 l3 blue custom_stock_search" @click.prevent="ajax_getStock" style="transform:translateY(80%)">Search</button>
        <div class="col s12">
            <table class="centered striped">
                <thead>
                    <tr>
                        <th @click="sortName">Name</th>
                        <th @click="sortRef">Reference</th>
                        <th @click="sortSend_qty">Send</th>
                        <th class="teal-text">Retail Price</th>
                        <th class="cyan-text">Wholesale Price</th>
                        <th class="teal-text">Total Retail</th>
                        <th class="cyan-text">Total Wholesale</th>
                        <th @click="sortShop">Shop</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- <tr v-for="(list,index) in custom_lists">
                        <td>@{{list.name}}</td>
                        <td>@{{list.ref}}</td>
                        <td>
                            <input type="number" v-model="list.send" style="width:auto" class="center indigo-text">
                        </td>
                        <td>@{{list.retail_price}}</td>
                        <td>@{{list.wholesale}}</td>
                        <td class="teal-text text-darken-2">
                            <input type="number" v-model="list.send * list.retail_price" style="width:40%" class="center" disabled>
                        </td>
                        <td class="cyan-text text-accent-4">
                            <input type="number" v-model="list.send * list.wholesale" style="width:40%" class="center" disabled>
                        </td>
                        <td>@{{list.shopname}}</td>
                        <td>
                            <button class="btn red" @click.prevent="deleteThis(index)">Delete</button>
                        </td>
                    </tr> --}}
                </tbody>
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
  <div id="replishment_list"></div>
  <div class="list_action"></div>

</div>



@endif
@stop

@push('replishment_js')
    <script type="text/javascript" src="{{URL::asset('js/plugin/jquery.tabletoCSV.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
@endpush
