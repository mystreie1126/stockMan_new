@extends('template')

@section('content')


<div class="container">
    <p class="flow-text">Please select the Replishment option to start:</p>
    <div id="rep_option">
      <!-- Dropdown Trigger -->
      <a class='dropdown-button btn-large' data-activates='dropdown1'>Replishment Option</a>

      <!-- Dropdown Structure -->
      <ul id='dropdown1' class='dropdown-content'>
        <li><a href="javascript:void(0)" class="get_sales_form "><i class="material-icons">credit_card</i>Sales item List</a></li>
        <li><a href="#custom_rep_form" class="get_custom_form modal-trigger"><i class="material-icons">note_add</i>Additonal item</a></li>
      </ul>
    </div>



<div id="rep_form" style="margin-bottom:20px">
{{--1 .Sales submit form--}}
        <div class="rep_type_form row hide" id="sales_rep_form">
          <div class="col s12 m12 l12">
              <select id="rep_shop_name">
                <option disabled selected>Choose a Shop</option>
                <option value="26">Mill</option>
                <option value="25">Kiosk</option>
                <option value="27">Athlone</option>
                <option value="28">EyerSquare</option>
                <option value="29">Arthus Quay</option>
                <option value="30">Gorey</option>
                <option value="31">Parkway</option>
                <option value="32">Cresent</option>
                <option value="33">Wexford</option>
                <option value="34">MarketCross</option>
                <option value="36">Douglas</option>
                <option value="37">Millfield</option>
                <option value="39">Blackpool</option>
              </select>
              <label>Select Branches</label>
          </div>

        	<div class=" col s3 m3 l3">
{{--             <input type="datetime-local" placeholder="Date From">
 --}}        		<input type="text" class="datepicker rep_start_date" placeholder="Date From">
         </div>


        	<div class=" col s3 m3 l3">
        		<input type="text" class="datepicker rep_end_date" placeholder="Date To">
          </div>

          <div class=" col s3 m3 l3">
        		<input type="text" class="timepicker rep_start_time" placeholder="Time From">
          </div>

          <div class=" col s3 m3 l3">
        		<input type="text" class="timepicker rep_end_time" placeholder="Time To">
          </div>

        	<div class="col s4 m3 l3 get_chart_btn">
        		<button class="btn green" id="rep_get_sales">Creat List</button>
        	</div>

        </div> {{--sales submit form--}}


{{--2 .custom submit form--}}

        <div class="modal modal-fixed-footer rep_type_form" id="custom_rep_form">
          <div class="modal-content row">
            <h4>Modal Header</h4>
            <p>A bunch of text</p>
          </div>
          <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
          </div>
        </div>{{--custom submit form--}}




  </div>{{--end of all rep-form--}}




<div id="rep_saved_list">
  <table class="rep_saved_list_table striped">
    <thead>
      <tr>
          <th class="center">Shop</th>
          <th class="center">Product Type(s)</th>
          <th class="center">Last Update</th>
          <th class="center">Action</th>
      </tr>
    </thead>

      <tbody class='rep_saved_list_table_details'>

          {{-- <tr>
            <td class="center">Mill</td>
            <td class="center">26</td>
            <td class="center">2018 11-29 10:23:34</td>
            <td class="center">
              <button class="btn green waves-effect waves-light send_action_btn">Send</button>
              <input type="hidden" value=''>
              <button class="btn indigo waves-effect waves-light send_action_btn">Export</button>
              <button class="btn red waves-effect waves-light send_action_btn">Delete</button>
            </td>
          </tr> --}}


     </tbody>
  </table>
  <div class="progress loading-effect">
      <div class="indeterminate"></div>
  </div>
</div>

<div id="rep_send_list">
    <p class="rep_sale_table_msg"></p>
    <table class="rep_sale_table hide striped">
      <thead>
        <tr>
            <th>Barcode</th>
            <th>Name</th>
            <th>Sold Qty</th>
            <th>Send Qty</th>
            <th>HQ Qty</th>
            <th>Modified</th>
            <th>Shop</th>
        </tr>
      </thead>

        <tbody class='rep_sale_form_details'>



       </tbody>
    </table>
    <div class="rep-sale_form_btn right">
      {{-- <a class="waves-effect waves-light btn right  indigo darken-4 save_sale_to_list"><i class="material-icons right">save</i>Save</a> --}}
    </div>
  </div>{{--end of send list--}}
</div> {{--end of container--}}

<table class="export_table hide">
  <thead>
    <tr>
        <th>Name</th>
        <th>reference</th>
        <th>Send Quantity</th>
    </tr>
  </thead>

    <tbody class='export-table_details'>



   </tbody>
</table>


<div class="container missing-block">

<h4 class="red-text"><span class="missing_shop"></span> Missing Products from list</h4>
<table class="missing_products striped">
  <thead>
    <tr>
        <th>Name</th>
        <th>reference</th>
        <th>Send Quantity</th>
    </tr>
  </thead>

    <tbody class='missing_products_details'>



   </tbody>
</table>
<button class="export-missing btn red accent-1">Export</button>

</div>
@endsection


@push('replishment_js')
  <script type="text/javascript" src="{{URL::asset('js/replishment.js')}}"></script>
{{-- <script type="text/javascript" src="{{URL::asset('js/replishment.vue.js')}}"></script>
 --}} @endpush
