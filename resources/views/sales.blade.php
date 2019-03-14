@extends('template')

@section('content')




<div class="row">
	<section class="col l9">
		<div class="row">

			<div id="product_box1" class="col s12 box">
				<form class="row" id="product_form">
					<div class="input-field col s3 m3 l3">
					    <select>
					      <option value="" disabled selected>Option</option>
					      <option value="1">Option 1</option>
					      <option value="2">Option 2</option>
					     {{--  <option value="3">Option 3</option> --}}
					    </select>
					    <label>Product editing with in and out</label>
					</div>

					<div class="input-field col s7 m7 l7">
						<div>
							<input id="ref-input" type="text" required>
			          		<label for="ref-input" data-error="wrong" data-success="right" >Reference</label>
						</div>

			        </div>
			        <div class=" col s2 m2 l2">
			        	<button class="btn product_btn ">Search</button>
			        </div>



				</form>
			</div>
		</div>
	







	</section>


<div class="col l3">
		<ul class="collection recent_orders with-header">
			
			<li class="collection-header center">Recent Partner Orders</li>

			<div id="recent_order_response">
				{{-- <li class="collection-item recent_orders_list">
				<div class="tt">
					<span class="title recent_order_title green-text">Title</span>
					<span class="title recent_order_status">Status</span>
				</div>
	      		<span class="recent_order_ref">First Line</span> <br>
				<span class="recent_order_date">Second Line</span>
				<a href="!#" class="secondary-content redirect_to_target_order"><i class="material-icons">send</i></a>
	    	</li>  --}}
			<div class="progress center">
		      <div class="indeterminate"></div>
		    </div>
        
        
			</div>
			
		</ul>
 		</div>

</div>

@push('sale_js')
	<script type="text/javascript" src="{{URL::asset('js/sale.js')}}"></script>
@endpush

@stop
