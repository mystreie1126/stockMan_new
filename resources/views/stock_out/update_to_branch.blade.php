@extends('template')
@section('content')

@if(Auth::check())

<div class="container">

    <h5 style="margin:30px 0;font-family:'arial'">Update the Stock quantities to branch stock</h5>
    <div class="">
        @if($shops_by_sale->count() > 0)
            <h5 class="center">Upload to branch stock with <span class=" red-text text-accent-2">replishment by sale</span> records</h5>
            <ul class="collapsible" data-collapsible="accordion">
            @foreach($shops_by_sale as $shop)
                <li>
                    <div class="collapsible-header" style="display:flex; justify-content:space-between;">
                            <i class="material-icons">local_shipping</i>
                            <span class="teal-text" style="font-size:1.4rem">{{$shop->shop_name}}</span>
                            <form action="{{route('delete_before_update_to_branch')}}" method="post">
                                <input type="hidden" name="by_sale" value="1">
                                <input type="hidden" name="shop_id" value="{{$shop->shop_id}}">
                                <input type="hidden" value="{{$shop->shop_name}}">
                                <button class="btn delete_before_update_to_branch red">Delete</button>
                            </form>
                    </div>
                    <div class="collapsible-body" style="overflow:auto">
                        <form action="{{route('update_to_branch')}}" method="post">
                            <table class="striped centered">
                                <thead>
                                    <tr>
                                      <th>Shop Name</th>
                                      <th>Barcode</th>
                                      <th>Product Name</th>
                                      <th>Send quantity</th>
                                      <th>Sales From</ht>
                                      <th>Sales To</th>
                                      <th class="hide">test</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <p>
                                        Total {{$shop->detail->count()}} types of product
                                    </p>
                                    @foreach($shop->detail as $detail)
                                         <tr>
                                           <td>{{$detail->shopname}}</td>
                                           <td class="amber-text darken-4">{{$detail->barcode}}</td>
                                           <td class="teal-text darken-4">{{$detail->product_name}}</td>
                                           <td class="indigo-text">{{$detail->updated_quantity}}</td>
                                           <td>{{$detail->selected_startDate}}</td>
                                           <td>{{$detail->selected_endDate}}</td>
                                           <td class="hide">
                                               <input type="hidden" name="by_sale" value="1">
                                               <input type="hidden" name="shop_id" value="{{$shop->shop_id}}">
                                           </td>
                                         </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn upload_qty_to_branch">Upload</button>

                        </form>
                    </div>
                </li>
             @endforeach
            </ul>
        @else
          <p class="center red-text text-darken-4">No saved replishment by sale records needs to upload </p>
        @endif

    </div>
    <hr>

</div>

@endif
@stop

@push('update_to_branch')
    <script type="text/javascript" src="{{URL::asset('js/update_to_branch.js')}}"></script>
@endpush
