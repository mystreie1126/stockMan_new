<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use App\Model\Order\Online_order;
use DB;
use Mail;
use PDF;
use App\Mail\orderToPosEmail;


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
               ->whereNotIn('current_state',[5,4,6,2])
               ->orderBy('date_add','desc')
               ->get();

        //return $orders;

        return view('order.pos_partner_order',compact('orders'));
    }

    public function order_to_pos(Request $request){
        $order_details = DB::table('ps_order_detail')
                        ->select('product_reference as ref','product_name','product_quantity')
                        ->where('id_order',intval($request->order_id))
                        ->get();

        $order_ref = DB::table('ps_orders')->where('id_order',intval($request->order_id))->value('reference');
        $email     = DB::table('c1ft_stock_manager.sm_pos_partners')->where('rockpos_shop_id',$request->pos_shop_id)->value('email');
        $shop_name = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',$request->pos_shop_id)->value('name');

         $no_pass   = [];
         $wholesale = [];
         $retail    = [];
        foreach($order_details as $detail){
            if(Common::get_branchStockID_by_ref($detail->ref,intval($request->pos_shop_id)) == null){
                array_push($no_pass,$detail);
            }
            array_push($wholesale,Common::get_wholesale_price_by_ref($detail->ref)*intval($detail->product_quantity));
            array_push($retail,Common::get_retail_price_by_ref($detail->ref)*intval($detail->product_quantity));

            $detail->wholesale_price = (Common::get_wholesale_price_by_ref($detail->ref) > 0) ? number_format(Common::get_wholesale_price_by_ref($detail->ref),2):"Does't have";
            $detail->retail_price    = (Common::get_retail_price_by_ref($detail->ref) > 0)    ? number_format(Common::get_retail_price_by_ref($detail->ref),2): "Does't have";
        }


        if(count($no_pass) == 0){
            $total_wholesale = array_sum($wholesale);
            $total_retail    = array_sum($retail);
            foreach($order_details as $detail){
                DB::table('c1ft_pos_prestashop.ps_stock_available')
                ->where('id_stock_available',Common::get_branchStockID_by_ref($detail->ref,intval($request->pos_shop_id)))
                ->increment('quantity',intval($detail->product_quantity));
            }

            DB::table('ps_orders')->where('id_order',intval($request->order_id))->update(['current_state'=>4]);

             Mail::to($email)
             ->cc(['Keiran.brown@funtech.ie'])
             ->send(new orderToPosEmail($order_details,$total_wholesale,$total_retail,$order_ref,$shop_name));


            return redirect()->route('partner_order');
        }else{
            return 'Error on sending emails';
        }

    }




}
