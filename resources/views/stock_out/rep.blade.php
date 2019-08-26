@extends('template')

@section('content')



@if(Auth::check())

<div id="replishmentLists" style="margin:20px">

<div class="lds-spinner spinner-loader hide"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

  <h3></h3>
  <h5>Re Stock Options:</h5>
  {{-- stadard restock --}}
<ul class="collapsible" data-collapsible="accordion">

<li>
      <div class="collapsible-header"><i class="material-icons green-text">whatshot</i>Standard Restock</div>
      <div class="collapsible-body" id="standard_rep">

        <div class="row" >
           <div class="col s12 m4 l4">
            <span class="green-text text-lighten-3">Select Branch:</span>
            <select id="selected_standard_stock_shop">
              <option disabled selected>Choose a Shop</option>
                  @foreach($shops as $shop)
                    <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                  @endforeach
            </select>
          </div>

          <button class="green btn col s6 m2 l2" @click.prevent="get_standard_list" style="transform:translate(10%,80%)">Create List</button>

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
                       <tr v-for="(list,index) in standard_list">
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
                       </tr>
                   </tbody>
                   <button class="btn amber right" v-if="showbtn" @click.prevent="exportList">Export to CSV</button>
               </table>
          </div>
          <button class="btn blue submitStandardList" v-if="showbtn" @click.prevent="submitStandardList">Submit</button>
      </div>
    </li>


<li>
  <div class="collapsible-header"><i class="material-icons teal-text">filter_drama</i>Re-stock by Sales</div>
  <div class="collapsible-body" id="sale_rep">

    <div id="sales_rep_form">
      <span class="flow-text  cyan-text text-darken-3 ">Re-Stock by Sales</span>
      <div class="row" >
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
            <button type="button" v-on:click.prevent="getsalesList" class="btn s12 m3 l3" id="createSalesList" style="transform:translateY(80%)">Create List</button>
      </div>
      <div class="list_showcase">
          <table class="centered striped">
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Reference</th>
                      <th class="indigo-text">Send</th>
                      <th class="teal-text">Retail Price</th>
                      <th class="cyan-text">Wholesale Price</th>
                      <th class="teal-text">Total Retail</th>
                      <th class="cyan-text">Total Wholesale</th>
                      <th>Shop</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="(list,index) in sales_list">
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
                      <td>@{{list.shop_name}}</td>
                  </tr>
              </tbody>
              <button class="btn amber exportSalesBtn" v-if="showbtn" @click.prevent="exportList">Export to CSV</button>
          </table>
      </div>
      <button class="btn blue submitSaleList" v-if="showbtn" @click.prevent="submitSaleList">Submit</button>
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
                    <th class="teal-text">Retail Price</th>
                    <th class="cyan-text">Wholesale Price</th>
                    <th class="teal-text">Total Retail</th>
                    <th class="cyan-text">Total Wholesale</th>
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
                </tr>
            </tbody>
            <button class="btn blue save_custom_rep" @click.prevent="submitThis">Submit</button>
        </table>
    </div>
    </div>
</div>
</li>

{{-- parts restock --}}

<li>
  <div class="collapsible-header"><i class="material-icons pink-text text-darken-4">build</i>Parts Restock
      <div class="preloader-wrapper small active preloader_parts hide">
       <div class="spinner-layer spinner-teal-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>

  </div>

  <div class="collapsible-body" id="parts_standard">

    <div class="row" >
       <div class="col s12 m4 l4">
        <span class="green-text text-lighten-3">Select Branch:</span>
        <select id="parts_standard_stock_shop">
                <option value="43">Northside</option>
                <option value="27">Athlone</option>
                {{-- <option value="41">Test</option> --}}
        </select>
      </div>

      <button class="pink pink-darken-4 btn col s6 m2 l2" @click.prevent="get_Parts_standardList" style="transform:translate(10%,80%)">Create List</button>

      <div class="list_showcase">
           <table class="centered striped">
               <thead>
                   <tr>
                       <th>Parts Name</th>
                       <th>standard</th>
                       <th>Current Stock</th>
                       <th>send</th>
                   </tr>
               </thead>
               <tbody>
                   <tr v-for="(list,index) in standard_list">
                       <td>@{{list.name}}</td>
                       <td>@{{list.standard}}</td>
                       <td>@{{list.quantity}}</td>
                       <td>
                          <input type="number" v-model="list.send" style="width:40%" class="center indigo-text">
                       </td>
                   </tr>
               </tbody>
               <button class="btn amber right" v-if="showbtn" @click.prevent="exportList">Export to CSV</button>
           </table>
      </div>
      <button class="btn blue sendParts" v-if="showbtn" @click.prevent="sendParts">Submit</button>
  </div>
</li>

{{--stock tracking options--}}


{{-- 1.Tracking product by manufactor--}}
<li>
  <div class="collapsible-header">
      <i class="material-icons teal-text">business</i>
      Track Stock By Manufactor

      <div class="preloader-wrapper small active preloader_teal hide">
       <div class="spinner-layer spinner-teal-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>

  </div>
  <div class="collapsible-body">

    <div id="tracking_by_manufacturer">
      <span class="flow-text  cyan-text text-darken-3">Track Stock in and out By Manufactor</span>
      <div class="row">
          <div class="col s12 m3 l3">
              <span class="indigo-text text-lighten-3">Select Manufactor:</span>
              <select id="manufactors">
                <option disabled selected>Choose a manufactor</option>
                  @foreach($manufactors as $manufactor)
                    <option value="{{$manufactor->id_manufacturer}}">{{$manufactor->name}}</option>
                  @endforeach
              </select>
           </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">Start datetime:</span>
               <input type="date" id="selected_start_date" v-model="startTime">
           </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">End datetime:</span>
               <input type="date" id="selected_end_date" v-model="endTime">
           </div>
           <button type="button" v-on:click.prevent="getBrandProcutList" class="btn s12 m3 l3" style="transform:translateY(80%)">Go!</button>

      </div>
      <div class="list_showcase">
          <table class="centered striped">
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Reference</th>
                      <th class="indigo-text">Total StockIn</th>
                      <th class="purple-text darken-4">Warehouse Stock</th>
                      <th class="red-text">Total Send</th>
                      <th class="green-text">Branch Sold</th>
                      <th class="teal-text">Online Order</th>
                      <th class="purple-text text-accent-3 bold">Warehouse Standard(by last 5 weeks)</th>
                      <th class="brown-text text-lighten-2">Unit Wholesale</th>
                      <th class="brown-text text-darken-3">Total Wholesale</th>
                      <th class="blue-grey-text text-lighten-2">Unit Retail</th>
                      <th class="blue-grey-text text-darken-4">Total Retail</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="(list,index) in product_lists">
                      <td>@{{list.name}}</td>
                      <td>@{{list.ref}}</td>
                      <td>@{{list.total_stockIn}}</td>
                      <td class="purple-text darken-4">@{{list.warehouse_stock}}</td>
                      <td>@{{list.total_send}}</td>
                      <td class="green-text">@{{list.branch_sold}}</td>
                      <td class="teal-text">@{{list.online_order}}</td>
                      <td class="purple-text text-accent-3 bold">@{{list.warehouse_standard}}</td>
                      <td v-bind:style="[list.wholesale > 0 ?{'border':'none'}:{'border':'2px solid red'}]" class="brown-text text-lighten-2">@{{list.wholesale}}</td>
                      <td class="brown-text text-darken-3">@{{list.total_wholesale}}</td>
                      <td class="blue-grey-text text-lighten-2">@{{list.retail}}</td>
                      <td class="blue-grey-text text-darken-4">@{{list.total_retail}}</td>
                  </tr>
              </tbody>

              <button class="btn amber right" @click.prevent="exportList">Export to CSV</button>
          </table>
      </div>
    </div>
  </div>
</li>

{{-- 2.tracking stock by category --}}

<li>
  <div class="collapsible-header">
      <i class="material-icons blue-text text-darken-3">content_paste</i>
      Track Stock By Categories

      <div class="preloader-wrapper small active preloader_blue hide">
       <div class="spinner-layer spinner-blue-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>

  </div>
  <div class="collapsible-body">

    <div id="tracking_by_category">
      <span class="flow-text blue-text text-darken-3">Track Stock By Categories</span>
      <div class="row" >
          <div class="col s12 m3 l3">
              <span class="indigo-text text-lighten-3">Select Categories:</span>
              <select id="categories">
                    <option value="1">Tempered Glass</option>
                    <option value="2">Leather Case</option>
              </select>
           </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">Start datetime:</span>
               <input type="date" id="selected_start_date" v-model="startTime">
           </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">End datetime:</span>
               <input type="date" id="selected_end_date" v-model="endTime">
           </div>
           <button type="button" v-on:click.prevent="getProductInfoByCategory" class="btn s12 m3 l3 blue darken-3" style="transform:translateY(80%)">Go!</button>
      </div>
      <div class="list_showcase">
          <table class="centered striped">
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Reference</th>
                      <th class="indigo-text">Total StockIn</th>
                      <th class="purple-text darken-4">Warehouse Stock</th>
                      <th class="red-text">Total Send</th>
                      <th class="green-text">Branch Sold</th>
                      <th class="teal-text">Online Order</th>
                      <th class="purple-text text-accent-3 bold">Warehouse Standard(by last 5 weeks)</th>
                      <th class="brown-text text-lighten-2">Unit Wholesale</th>
                      <th class="brown-text text-darken-3">Total Wholesale</th>
                      <th class="blue-grey-text text-lighten-2">Unit Retail</th>
                      <th class="blue-grey-text text-darken-4">Total Retail</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="(list,index) in product_lists">
                      <td>@{{list.name}}</td>
                      <td>@{{list.reference}}</td>
                      <td>@{{list.stock_in}}</td>
                      <td class="purple-text darken-4">@{{list.warehouse_stock}}</td>
                      <td>@{{list.total_send}}</td>
                      <td class="green-text">@{{list.store_sold}}</td>
                      <td class="teal-text">@{{list.online_order}}</td>
                      <td class="purple-text text-accent-3 bold">@{{list.warehouse_standard}}</td>
                      <td v-bind:style="[list.wholesale > 0 ?{'border':'none'}:{'border':'2px solid red'}]" class="brown-text text-lighten-2">@{{list.wholesale}}</td>
                      <td class="brown-text text-darken-3">@{{list.total_wholesale}}</td>
                      <td class="blue-grey-text text-lighten-2">@{{list.retail}}</td>
                      <td class="blue-grey-text text-darken-4">@{{list.total_retail}}</td>
                  </tr>
              </tbody>
              <button class="btn blue darken-4 right" @click.prevent="exportList">Export to CSV</button>
          </table>
      </div>
    </div>
  </div>
</li>

{{-- 3.Tracking stock in out by single product--}}


<li>
  <div class="collapsible-header">
      <i class="material-icons amber-text text-darken-2">store</i>
      Track Stock By Single Product

      <div class="preloader-wrapper small active preloader_yellow hide">
       <div class="spinner-layer spinner-yellow-only">
         <div class="circle-clipper left">
           <div class="circle"></div>
         </div><div class="gap-patch">
           <div class="circle"></div>
         </div><div class="circle-clipper right">
           <div class="circle"></div>
         </div>
       </div>
     </div>

  </div>
  <div class="collapsible-body">

    <div id="tracking_by_singleProduct">
      <span class="flow-text  amber-text text-darken-3">Track Stock By Single Product</span>
      <div class="row" >
          <div class="col s12 m3 l3 input-field" style="transform:translateY(10%)">
              <input type="text" v-model="search">
              <label for="custom_stock_ref">Reference or Name</label>
          </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">Start datetime:</span>
               <input type="date" id="selected_start_date" v-model="startTime">
           </div>

           <div class="col s12 m3 l3" class="datetime">
               <span class="indigo-text text-lighten-3">End datetime:</span>
               <input type="date" id="selected_end_date" v-model="endTime">
           </div>
           <button type="button" v-on:click.prevent="getSingleProductInfo" class="btn s12 m3 l3 amber" style="transform:translateY(80%)">Go!</button>
      </div>
      <div class="list_showcase">
          <table class="centered striped">
              <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Reference</th>
                      <th class="indigo-text">Total StockIn</th>
                      <th class="purple-text darken-4">Warehouse Stock</th>
                      <th class="red-text">Total Send</th>
                      <th class="green-text">Branch Sold</th>
                      <th class="teal-text">Online Order</th>
                      <th class="purple-text text-accent-3 bold">Warehouse Standard(by last 5 weeks)</th>
                      <th class="brown-text text-lighten-2">Unit Wholesale</th>
                      <th class="brown-text text-darken-3">Total Wholesale</th>
                      <th class="blue-grey-text text-lighten-2">Unit Retail</th>
                      <th class="blue-grey-text text-darken-4">Total Retail</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="(list,index) in product_lists">
                      <td>@{{list.name}}</td>
                      <td>@{{list.ref}}</td>
                      <td>@{{list.stock_in}}</td>
                       <td class="purple-text darken-4">@{{list.warehouse_stock}}</td>
                      <td>@{{list.total_send}}</td>
                      <td class="green-text">@{{list.store_sold}}</td>
                      <td class="teal-text">@{{list.online_order}}</td>
                      <td class="purple-text text-accent-3 bold">@{{list.warehouse_standard}}</td>
                      <td class="brown-text text-lighten-2">@{{list.wholesale}}</td>
                      <td class="brown-text text-darken-3">@{{list.total_wholesale}}</td>
                      <td class="blue-grey-text text-lighten-2">@{{list.retail}}</td>
                      <td class="blue-grey-text text-darken-4">@{{list.total_retail}}</td>
                  </tr>
              </tbody>
          </table>
      </div>
    </div>
  </div>
</li>

{{-- 4.send email --}}

<li>
  <div class="collapsible-header">
      <i class="material-icons red-text text-darken-3">store</i>
      Send Email Notification

      <div class="preloader-wrapper small active preloader_red hide">
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

  </div>
  <div class="collapsible-body">

    <div id="sendEmailNotification">
      <span class="flow-text red-text red-darken-3">Send Email</span>
      <div class="row" >

          <div class="col s12 m3 l3">
              <span class="indigo-text text-lighten-3">Select Product Categories:</span>
              <select id="product_categories">
                    <option disabled selected>Choose a Category</option>
                    <option value="1">All Tempered Glass</option>
                    <option value="2">All Leather Case</option>
                    <option value="3">All USMAS Products</option>
              </select>
           </div>
           <button type="button" v-on:click.prevent="sendEmail" class="btn s12 m3 l3 red darken-3 sendEmail" style="transform:translateY(80%)">Send Email</button>
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
