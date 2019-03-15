@extends('template')

@section('content')


<div class="row">
	<section class="col l9" style="margin-top:15px">
					<div class="col s12 m3 l3">
					    <select id="shop_name">
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


					<div class=" col s4 m3 l3 date_class">
						<input type="text" class="datepicker start_date" placeholder="From">
			    </div>


					<div class=" col s4 m3 l3 date_class">
						<input type="text" class="datepicker end_date" placeholder="To">
			    </div>

					<div class="col s4 m3 l3 get_chart_btn">
						<button class="btn get_allsales_chart_btn" disabled id="get_chart">Creat Chart</button>
					</div>


			<div id="allshop_pos_sale_chart" class="col s8 l8"></div>
			<div id="shop_weekly_sale_chart" class="col s8 l8"></div>



	</section>


<div class="col l3">
		<ul class="collection recent_orders with-header">

			<li class="collection-header center">Recent Partner Orders</li>

			<div id="recent_order_response">
			<div class="progress center">
		      <div class="indeterminate"></div>
		    </div>


			</div>

		</ul>
 		</div>

</div>
@push('export_topSale')




	<div class="fixed-action-btn">
	    <a class="btn-floating btn-large red">
	      <i class="material-icons">mode_edit</i>
	    </a>
	    <ul>
	      <li>
	        <a href="#export_topSale_csv" class="modal-trigger btn-floating blue sell_page_editing">
	          <i class="material-icons">cloud_download</i>
	        </a>
	      </li>
	    </ul>
	  </div>

		<div id="export_topSale_csv" class="modal">
    <div class="modal-content export_qty_modal row">
			<div class="col s4 m4 l4">
					<select class="select_top_qty_sold">
						<option value="100" class="selected">Top 100</option>
						<option value="200">Top 200</option>
						<option value="300">Top 300</option>
					</select>
					<label>Select Branches</label>
			</div>

			<div class="col s4 m4 l4">
					<input type="text" class="datepicker export_start_date" placeholder="From">
			</div>

			<div class="col s4 m4 l4">
					<input type="text" class="datepicker export_end_date" placeholder="From">
			</div>
    </div>
    <div class="modal-footer">
      {{-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a> --}}
			<button class="export_topSale_csv btn blue">Export to CSV</button>
    </div>
  </div>



	<table class="hide top_sale_product_table">
		<thead>
			<tr>
					<th>Barcode</th>
					<th>Name</th>
					<th>Sold Qty</th>
			</tr>
		</thead>

			<tbody class='top_sale_product_table_details'>
				<tr>
					<td></td>
					<td></td>
					<td></td>
			 </tr>
	</tbody>
	</table>


@endpush
@push('sale_js')
	<script type="text/javascript" src="{{URL::asset('js/sale.js')}}"></script>
@endpush

@stop
