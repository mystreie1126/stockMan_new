@extends('template')

@section('content')


<section>

	<div class="container">
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
					
			          	{{-- <input id="ref-input" type="text">
			          	<label for="ref-input" data-error="wrong" data-success="right">Reference</label> --}}
			        
			       
					
				</form>
			</div>
		</div>


	<div class="row ">
		
		<div id="product_box3" class="col s12 m12 l6 box">

				<div id="total_stock_compare_chart" style="height: 35vh; width:100%"></div>
			
		</div>
		<div id="product_box2" class="col s12 m12 l6 box">
			<div id="each_store_stock_chart"  style="height: 35vh; width: 100%;"></div>
		</div>
		<div id="product_box4" class="col s12 m12 l6 box">
			
            <div id="last-four-week-chart"  style="height: 35vh; width: 100%;"></div>
           {{--  <div id="last-three-week-chart" class='hide' style="height: 35vh; width: 100%;"></div>
            <div id="last-two-week-chart" class='hide' style="height: 35vh; width: 100%;"></div>
            <div id="last-one-week-chart" class='hide' style="height: 35vh; width: 100%;"></div> --}}

          

		</div>
		<div id="product_box5" class="col s12 m12 l6 box"></div>
		
	</div>
		
</div>	
	
</section>




@stop