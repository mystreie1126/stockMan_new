@extends('template')
@section('content')

<div class="countStock container">
	
{{-- 
	<div class="update_product row">
		<div class="input-field col s12 m12 l12">
	         <input id="last_name" type="text" class="validate" placeholder="reference">
        </div>
		
		<button class="btn col s12 l12">Search</button>
	</div>

	
	<div class="updated_stock row"></div>
	
</div> --}}

<div class="striped" id="branchStockList">
{{--   <thead>
    <tr>
        <th>Name</th>
        <th>reference</th>
        <th class="center">Quantity</th>
        <th class="center">Update</th>
    </tr>
  </thead> --}}
    <input type="text" id="myInput" placeholder="Search by name or reference(at least 2 characters)">

    <ul class="stockinfo_branch">
	{{-- 	<tr class="stockinfo_row">
			<th class="indigo-text">iOhone asdadqwedad a adadqweqdad</th>
			<th class="teal-text">2000007891</th>
			<th>
				<input type="number" class="update_stock_input center" required>
			</th>
			<th class="center">
				<button>Update</button>
			</th>
			<input type="hidden" value="" class="branch_product_id">
		</tr> --}}

   </ul>
</div>

@push('countStock')
  <script type="text/javascript" src="{{URL::asset('js/countstock.js')}}"></script>
@endpush
@stop