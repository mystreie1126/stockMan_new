@extends('template')

@section('content')
	<button type="button" class='bt btn' name="button"></button>
	<table class="striped total-sale ">

      	  <thead>
          <tr>
          	  <th>Order Ref</th>
          	  <th>Amount &euro;</th>
              <th>Card</th>
              <th>Cash</th>
              <th>Date</th>
          </tr>
        </thead>

        <tbody class='each-websales-record'>
          <tr>
          	<td>0</td>
          	<td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
         </tr>

		</tbody>
		</table>
@endsection
