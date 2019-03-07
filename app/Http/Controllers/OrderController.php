<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\HQ\Order;
use App\HQ\Order_detail as detail;
use App\HQ\Partner_order_history as PartnerOrder;
use App\HQ\Partner_rockpos_shop as PartnerPos;

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


	/* Get:: All order */
    public function allOrder(){
    	
    	$order = Order::with('buyer_group')->paginate(15);
    	
    	//return $order;
    	
    	return all_online_order_resource::collection($order);
    }

    /* Get:: Single order with order id */
    public function single_order($id){
    	$order = Order::find($id);
    	if($order){
    		return new all_online_order_resource($order);
    	}
    	
    }

    /* Get:: Single order details with order_id*/
    public function viewOrder_details($id){
    	$d = detail::where('id_order',$id)->get();
    	Order::paginate(10);
    	return order_details_resource::collection($d);
    }

    

    /* POST:: update partner stock with order_id */
    public function update_stock(Request $request){
    	
    	$partner = Order::findOrFail($request->order_id)->id_customer;
    	//$order_id,$shop_id
    	//return PartnerPos::findOrFail($partner)->get();
    	$partner_shop = PartnerPos::findOrFail($partner)->rockpos_shop_id;

    	//return $partner_shop;
    	if($partner_shop){
    		
    	
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

		 	$PartnerOrder = new PartnerOrder;

		 	$PartnerOrder->order_id = $request->order_id;
		 	$PartnerOrder->customer_id = Order::findOrFail($request->order_id)->id_customer;
		 	$PartnerOrder->customer_group = Order::findOrFail($request->order_id)->buyer_group;
		 	$PartnerOrder->created_at = date("Y-m-d H:i:s");
		 	$PartnerOrder->save();

		 	if($PartnerOrder->save()){
		 		Order::findOrFail($request->order_id)->update(['current_state' => 5]);
		 		return new updatePartnerStockResrouce($PartnerOrder);
		 	}
		 	else{
		 		return json()->response('can not update the stock');
		 	}

		}
		else{
			return json()->response('stock not found');
		}
    }



















}
