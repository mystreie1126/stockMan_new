@extends('template')

@section('content')




<div class="row">
	<section class="col l9">

	</section>


<div class="col l3">
		<ul class="collection recent_orders with-header">
			<li class="collection-header">Recent Partner Orders</li>
	    <li class="collection-item recent_orders_list">
				<div class="tt">
					  <span class="title recent_order_title green-text">Title</span>
						<span class="title recent_order_status">Status</span>
				</div>
	      <span class="recent_order_ref">First Line</span> <br>
				<span class="recent_order_date">Second Line</span>
				<a href="!#" class="secondary-content redirect_to_target_order"><i class="material-icons">send</i></a>
	    </li>

		</ul>
 		</div>

</div>

@push('sale_js')
	<script type="text/javascript" src="{{URL::asset('js/sale.js')}}"></script>
@endpush

@stop
