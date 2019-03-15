<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\HQ\Order;
use App\HQ\Order_detail as detail;
use App\HQ\Partner_order_history as PartnerOrder;
use App\HQ\Partner_rockpos_shop as PartnerShop;
use App\HQ\Customer as Customer;

use App\Partner\Order as sold;
use App\Partner\Order_detail as sold_detail;
use App\Partner\Stock as pos_stock;
use App\Partner\Shop as pos_shop;


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
    	$partner_shop = PartnerShop::findOrFail($partner)->rockpos_shop_id;

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


    public function recentOrders(){

    $data = DB::table('ps_orders as a')
            ->select('a.id_order','a.id_customer','a.reference','a.date_add','c.firstname','c.lastname','a.current_state')
            ->join('ps_customer_group as b','a.id_customer','b.id_customer')
            ->join('ps_customer as c','a.id_customer','c.id_customer')
            ->join('ps_order_state_lang as d','d.id_order_state','a.current_state')
            ->where('b.id_group',5)
            ->whereNotIn('a.id_customer',[2074,3102])
            ->orderBy('a.date_add','desc')
            ->limit(10)
            ->get();

    return $data;

    }




    public function searchOrderByRef($ref){


        $order = Order::where('reference','like','%'.$ref.'%')->first();

        if($order){

           $customer = Customer::find($order->id_customer);
           $customer_order = $customer->order->where('current_state','!=',6);

           $customer_totalOrder_amount = $customer_order->sum('total_paid_tax_incl');

           $isPartner = (PartnerShop::where('customer_id',$order->id_customer)->count() > 0) ? 'Yes':'No';

           $shop_id = PartnerShop::where('customer_id',$order->id_customer)->value('rockpos_shop_id');

           $owned_branch = $isPartner&&$shop_id!= 0 ? pos_shop::find($shop_id)->name : 'Null';

           $total_valid_order =  $customer_order->count();

           $mobile = DB::table('ps_address')->where('id_customer',$order->id_customer)->value('phone_mobile');

           $orderSince =  $customer_order->sortBy('date_add')->first()->date_add;
           $lastOrder = $customer_order->sortByDesc('date_add')->first()->date_add;

           $order_details = DB::table('ps_stock_available as a')
                 ->select('a.id_product','a.quantity as hq_qty','b.product_quantity as qty','b.product_name','b.product_reference')
                 ->join('ps_order_detail as b','a.id_product','b.product_id')
                 ->join('ps_orders as c','c.id_order','b.id_order')
                 ->where('c.reference','like','%'.$ref.'%')
                 ->get();
           $d = $order->first()->date_add;
           //return $customer_order;
           //return date("Y-m-d", strtotime($d));
           $orderChart_amount = $customer_order->pluck('total_paid_tax_incl');
           $orderChart_date  = $customer_order->pluck('date_add');
           return response()->json([ 'customer'=>$customer,
                                     'mobile'=>$mobile,
                                    'order'=>$order,
                                    'order_details' =>  $order_details,
                                    'isPartner'=>$isPartner,
                                    'branch'=>$owned_branch,
                                    'total_order_amount'=>$customer_totalOrder_amount,
                                    '$total_valid_order'=> $total_valid_order,
                                    'orderSince' => $orderSince,
                                    'lastOrder'=>$lastOrder,
                                    'chartAmount' => $orderChart_amount,
                                    'chartDate' => $orderChart_date
                                  ]);

        }


    }

 public function allsales(Request $request){

    $all_pos_sales = DB::connection('mysql2')
      ->table('ps_orders as a')
      ->select(DB::raw('sum(a.total_paid_tax_incl) as total'),'b.name')
      ->groupBy('a.id_shop')
      ->join('ps_shop as b','a.id_shop','b.id_shop')
      ->whereNotIn('a.id_shop',[1,41,35])
      ->where('a.date_add','>=',$request->date_from)
      ->where('a.date_add','<=',$request->date_to)
      ->get();

        $days = -70; $week = 10;

        $weeklysale = [];
        for($i = 0; $i < 10; $i++){
          $from  = date("Y-m-d h:i:s",strtotime($days. "day"));
          $to = date("Y-m-d h:i:s",strtotime(($days+7). "day"));
          $singleWeeksale = sold::whereBetween('date_add',[$from,$to])->where('id_shop',$request->id_shop)->sum('total_paid_tax_incl');
          array_push($weeklysale,['week'=> $week,'sale'=>$singleWeeksale]);
          $days+=7;
          $week -= 1;
        }


    $shop_name = DB::table('vr_confirm_payment')->where('rockpos_shop_id',$request->id_shop)->value('shop_name');
    return response()->json(['all_pos_sale'=> $all_pos_sales,'each_week_sale'=>$weeklysale,'name'=>$shop_name]);

   }

   public function topSalesQty(Request $request){

     $qty = DB::connection('mysql2')->table('ps_order_detail as a')
            ->select('a.product_name','a.product_reference',DB::raw('sum(a.product_quantity) as qty'))
            ->groupBy('a.product_name')
            ->join('ps_orders as b','a.id_order','b.id_order')
            ->where('b.date_add','>=',$request->date_from)
            ->where('b.date_add','<=',$request->date_to)
            ->orderBy('qty','desc')
            ->limit($request->num)
            ->get();

     return $qty;
   }









}
