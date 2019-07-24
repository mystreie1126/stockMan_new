<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use App\Model\Order\Online_order;
use DB;



/*
- view order both from funtech and rockpos
-

*/

class OrderController extends Controller
{

    private function pos_partners(){
        $query = DB::table('c1ft_stock_manager.sm_pos_partners')->pluck('ps_customer_id')->toArray();
        return $query;
    }

    public function pos_partner_order(){
        $orders = Online_order::with('detail','message','customer_detail')
               ->whereIn('id_customer',self::pos_partners())
               ->where('id_shop',11)
               ->whereNotIn('current_state',[5,4])
               ->orderBy('date_add','desc')
               ->get();

        //return $orders;

        return view('order.pos_partner_order',compact('orders'));
    }

    public function order_to_pos(Request $request){
        $order_details = DB::table('ps_order_detail')
                        ->select('product_reference as ref','product_quantity')
                        ->where('id_order',intval($request->order_id))
                        ->get();

         $no_pass =[];

        foreach($order_details as $detail){
            if(Common::get_branchStockID_by_ref($detail->ref,intval($request->pos_shop_id)) == null){
                array_push($no_pass,$detail);
            }
        }


        if(count($no_pass) == 0){
            foreach($order_details as $detail){
                DB::table('c1ft_pos_prestashop.ps_stock_available')
                ->where('id_stock_available',Common::get_branchStockID_by_ref($detail->ref,intval($request->pos_shop_id)))
                ->increment('quantity',intval($detail->product_quantity));
            }
            DB::table('ps_orders')->where('id_order',intval($request->order_id))->update(['current_state'=>4]);

            return redirect()->route('partner_order');
        }else{
            return redirect()->back()->with('error', true);
        }

    }




}
