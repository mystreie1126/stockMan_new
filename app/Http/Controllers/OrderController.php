<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\HQ\Order;
use App\HQ\Order_detail as detail;
use App\HQ\Partner_order_history as PartnerOrder;

use App\partner\Order as sold;
use App\partner\Order_detail as sold_detail;
use App\partner\Stock as pos_stock;


use App\Http\Resources\all_online_order_resource;
use App\Http\Resources\order_details_resource;
use App\Http\Resources\updatePartnerStockResrouce;


/*
- view order both from funtech and rockpos 
- 


*/

class OrderController extends Controller
{
    public function allOrder(){
    	
    	$order = Order::with('buyer_group')->paginate(15);
    	
    	//return $order;
    	
    	return all_online_order_resource::collection($order);
    }


    public function single_order($id){
    	$order = Order::find($id);
    	if($order){
    		return new all_online_order_resource($order);
    	}
    	
    }

    public function viewOrder_details($id){
    	$d = detail::where('id_order',$id)->get();
    	Order::paginate(10);
    	return order_details_resource::collection($d);
    }

    


    public function update_stock(Request $request){
    	

    	//$order_id,$shop_id

    	$items = DB::table('ps_order_detail as a')
	    	     ->select('a.product_reference',DB::raw('sum(a.product_quantity) as quantity'))
	    	     ->groupBy('product_reference')
	    	     ->where('id_order',$request->order_id)
	    	     ->get();
    	
		for($i = 0; $i < count($items); $i++){
      		 DB::connection('mysql2')->table('ps_stock_available as a')
	    	->select('a.id_product','ps_product.reference')
	    	->join('ps_product','a.id_product','ps_product.id_product')
	    	->where('a.id_shop',$request->shop_id)
	    	->where('ps_product.reference',$items[$i]->product_reference)
	    	->increment('a.quantity',$items[$i]->quantity);
  		}

  		
  		//check if customer is trader or partner, trader group is 5
  		//$PartnerOrder->customer_group = Order::findOrFail($request->order_id)->buyer_group;
	 	$PartnerOrder = new PartnerOrder;

	 	$PartnerOrder->order_id = $request->order_id;
	 	$PartnerOrder->customer_id = Order::findOrFail($request->order_id)->id_customer;
	 	$PartnerOrder->customer_group = Order::findOrFail($request->order_id)->buyer_group;
	 	$PartnerOrder->created_at = date("Y-m-d H:i:s");
	 	$PartnerOrder->save();

	 	if($PartnerOrder->save()){
	 		return new updatePartnerStockResrouce($PartnerOrder);
	 	}


    }
}
