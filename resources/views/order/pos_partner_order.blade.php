@if(Auth::check())
@extends('template')
@section('content')
    <div class="container">
        @if($orders->count() > 0)
            {{-- {{$orders->count()}} --}}
            @if (session()->has('error'))
                <p class="flow-text">Can not update this order</p>
            @endif
            <ul class="collapsible" data-collapsible="accordion">
                @foreach($orders as $order)
                    <li>
                        <div class="collapsible-header" style="display:flex">

                                <i class="material-icons indigo-text">flag</i>
                                <span class="indigo-text">{{$order->reference}}</span>
                                <i class="material-icons indigo-text"></i>
                                <span>{{round($order->total_paid_tax_incl)}} &euro;</span>
                                <i class="material-icons indigo-text"></i>
                                <span>{{$order->date_add}}</span>
                                <i class="material-icons indigo-text"></i>
                                <span>{{$order->customer_detail->email}}</span>
                        </div>
                        <div class="collapsible-body">
                            <form class="row" method="post" action="{{route('order_to_pos')}}">
                                <input type="hidden" name="order_id" value="{{$order->id_order}}">
                                <input type="hidden" name="pos_shop_id" value="{{$order->customer_detail->rockpos_shop_id}}">
                                @foreach($order->detail as $detail)
                                    <span class="col s8 indigo-text">{{$detail->product_name}}</span>
                                    <span class="col s2 indigo-text">{{$detail->product_reference}}</span>
                                    <span class="col s1 indigo-text" style="font-size:1.4rem; transform:translateY(-15%)">&times;</span>
                                    <span class="col s1">{{$detail->product_quantity}}</span>
                                @endforeach
                                {{csrf_field()}}
                                <button class="btn upload_qty_to_branch">Transfer to RockPos</button>
                                <p class="center">
                                    @foreach($order->message as $msg)
                                        <p>{{$msg->message}}</p>
                                    @endforeach
                                    
                                </p>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

        @else
            <p class="flow-text">No Recent Partner Orders</p>
        @endif
    </div>
    @push('update_to_branch')
        <script type="text/javascript" src="{{URL::asset('js/update_to_branch.js')}}"></script>
    @endpush
@stop
@endif
