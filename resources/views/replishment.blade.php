@extends('template')

@section('content')

<div class="container">
    <p class="flow-text">Please select the Replishment option to start:</p>
    <div id="rep_option">
      <!-- Dropdown Trigger -->
      <a class='dropdown-button btn' data-activates='dropdown1'>Replishment Option</a>

      <!-- Dropdown Structure -->
      <ul id='dropdown1' class='dropdown-content'>
        <li><a href="javascript:void(0)" class="get_sales_form"><i class="material-icons">view_module</i>by Sale</a></li>
        <li><a href="javascript:void(0)" class="get_order_form"><i class="material-icons">person</i>by Order</a></li>
        <li><a href="javascript:void(0)" class="get_custom_form"><i class="material-icons">cloud</i>Customize</a></li>
      </ul>
    </div>



<div id="rep_form" style="margin-bottom:20px">
{{--1 .Sales submit form--}}
        <div class="rep_type_form row hide" id="sales_rep_form">
          <div class="col s12 m12 l12">
              <select id="rep_shop_name">
                <option value="26" class="selected">Mill</option>
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
                <option value="39">Mill</option>
              </select>
              <label>Select Branches</label>
          </div>

        	<div class=" col s3 m3 l3">
        		<input type="text" class="datepicker rep_start_date" placeholder="Date From">
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

{{--2 .order submit form--}}
        <div class="rep_type_form row hide" id="order_rep_form">
            {{csrf_field()}}
            <div class="row">
                <div class="input-field col s6">
                  <i class="material-icons prefix">account_circle</i>
                  <input id="icon_prefix" type="text" class="validate" name="partener_order_ref" required>
                  <label for="icon_prefix">Partner Order Reference</label>
                </div>
                <button class="btn orange" style="transform:translateY(50%)">Create List</button>
            </div>
        </div>{{--order submit form--}}
{{--3 .custom submit form--}}

        <div class="rep_type_form row hide" id="custom_rep_form">
            {{csrf_field()}}


        </div>{{--custom submit form--}}
  </div>{{--end of all rep-form--}}


  <div id="rep_saved_list">

  </div>{{--end of saved list--}}

  <div id="rep_send_list">
    <p class="rep_sale_table_msg"></p>
    <table class="rep_sale_table striped">
      <thead>
        <tr>
            <th>Barcode</th>
            <th>Name</th>
            <th>Sold Qty</th>
            <th>Send</th>
            <th>HQ Stock</th>
            <th>Modified</th>
            <th>Shop</th>
        </tr>
      </thead>

        <tbody class='rep_sale_form_details'>
          <tr>
            <td>20000</td>
            <td>iphone</td>
            <td>10</td>
            <td><input type="number" value='2' class="validate rep_send_qty" disabled></td>
            <td>100</td>
            <td>No</td>
            <td>No</td>
            <td>
              <a href="javascript:void(0)" class="rep_update_qty indigo-text"><i class="material-icons">edit</i></a>
              <a href="javascript:void(0)" class="rep_confirm_update green-text" style="margin:0 10px"><i class="material-icons">done_all</i></a>
              <a href="javascript:void(0)" class="rep_remove_product red-text"><i class="material-icons">delete</i></a>
            </td>
         </tr>
       </tbody>
    </table>
  </div>{{--end of send list--}}


</div> {{--end of container--}}


@endsection


@push('replishment_js')
  <script type="text/javascript" src="{{URL::asset('js/replishment.js')}}"></script>
@endpush
