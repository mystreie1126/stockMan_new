@extends('template')

@section('content')



@if(Auth::check())

<div class="container" id="replishmentLists">
  <div class="sales_rep_form">
    <p class="flow-text  cyan-text text-darken-3 ">Re-stock items:</p>
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
  {{-- end of sales replishment form--}}
  <button type="button" class="btn-large indigo right" v-show="showButton" @click.prevent="exportList">Export to CSV</button>


  <div id="example-table"></div>

  <div class=" row">
     <div class="col s12">
       <table class="centered bordered">
         <thead>
           <tr>
             <th>Name</th>
             <th>Reference</th>
             <th>Sold Quantity</th>
             {{-- <th>HQ Quantity</th> --}}
             <th>Standard Quantity</th>
             <th>Actual Stock</th>
             <th class="red-text">Send Quantity</th>
           </tr>
         </thead>
         <tbody id="test_list">

           {{-- <tr >
             <td>iPhone 6 commuter case verlong asdas asdasdadasasdadsas</td>
             <td>100789</td>
             <td>56</td>
             <td class="input-field">
               <input type="text" class="send_qty center red-text" name="" value="">
             </td>
             <td>100</td>
           </tr> --}}

           {{-- <tr v-for="list in lists">
             <td>@{{list.name}}</td>
             <td>@{{list.ref}}</td>
             <td class="green-text">@{{list.soldQty}}</td>
             <td class="indigo-text">@{{list.standard_qty}}</td>
             <td>
                <div v-if="list.checked !== null">
                  <p class="green-text">@{{list.branch_qty}}</p>
                </div>
                <div v-else>
                    <p class="red-text">NO</p>
                </div>
             </td>
             <th class="input-field center">
                <input type="text" class="send_qty center" v-model="list.send" v-bind:style="list.checked !== null ? 'border:3px solid green;':'border:3px solid red;'">
             </th>
           </tr> --}}
         </tbody>
       </table>
       <p></p>
       <button class="btn-large right" @click.prevent="saveTheList" id="rep_salelist_submit">Sumbit</button>

<button type="button" id="download-csv">download</button>

     </div>
  </div>

  <div class="center preloader" v-show="list_loading">
    <div class="progress">
      <div class="indeterminate"></div>
    </div>
  </div>



</div>




@endif
@stop

@push('replishment_js')
  <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.7/dist/js/tabulator.min.js"></script>
<script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
@endpush
