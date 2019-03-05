<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\HQ\Order;
use App\HQ\Order_detail as detail;
use App\partner\Order as sold;
use App\partner\Order_detail as sold_detail;

use App\Http\Resources\view_online_order_resource;
use App\Http\Resources\order_details_resource;


/*
- view order both from funtech and rockpos 
- 


*/

class OrderController extends Controller
{
    public function checkOrder(){
    	
    	$order = Order::paginate(10);
    
    	
    	return view_online_order_resource::collection($order);
    }

    public function viewOrder_details($id){
    	$d = detail::where('id_order',$id)->get();
    	Order::paginate(10);
    	return order_details_resource::collection($d);
    }
}
