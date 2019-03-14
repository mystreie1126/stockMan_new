@extends('template')

@section('content')
<div class="container">
	<div class="row">
		<div id="product_box1" class="col s12 box">
			<form class="row" id="product_form">
				<div class="input-field col s7 m7 l7">
					<div>
						<input id="order-ref-input" type="text" required>
		          <label for="ref-input" data-error="wrong" data-success="right">Order Reference</label>
					</div>
		      </div>
		      <div class=" col s2 m2 l2">
		        	<button class="btn product_btn indigo " id="searchOrderInfoByRef">Search</button>
		      </div>
			</form>
		</div>
	</div>


	<section class="order_details row">

		{{-- <table class="striped col s12 order_details__table">


				<thead>
				<tr>
						<th>Product Ref</th>
						<th>Name</th>
						<th>Quantity</th>
						<th>Total(Tax.inc)</th>
				</tr>
			</thead>

			<tbody class='order_products'>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
			 </tr>
	</tbody>
	</table> --}}



	<div class="col s12 m6 l6">
		<div class="customer_form_col">

		</div>

	</div>

	<div class="col s12 m6 l6">
		<div id="customer_order_history_chart"></div>

	</div>

	<div class="col s12 m6 l6">
		<form class="order_form_col" method="post">
			{{ csrf_field() }}
		</form>
		<div class="send_btn"></div>
	</div>

	<div id="modal1" class="modal modal-fixed-footer">
		<form class="" action="index.html" method="post">
		    <div class="modal-content">

					<table class="striped order_details_table_modal ">
			      <thead>
		          <tr>
		          	  <th>Barcode</th>
		          	  <th>Name</th>
		              <th>Product_Qty</th>
									<th>HQ_Qty_left</th>
		          </tr>
			      </thead>

			        <tbody class='order_details_modal'>
			          <tr>
			          	<td></td>
			          	<td></td>
			            <td></td>
			            <td></td>
			         </tr>
					</tbody>
					</table>
		    </div>
		    <div class="modal-footer">
					<a href="javascript:void(0)" class="btn orderCSV">Export to CSV</a>
					<button name="button" class="btn orange" disabled>Replishment</button>
		      {{-- <button class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</button> --}}

		    </div>
			</form>
  </div>


	</section>




@push('order_js')
	<script type="text/javascript" src="{{URL::asset('js/order.js')}}"></script>
@endpush
@stop
