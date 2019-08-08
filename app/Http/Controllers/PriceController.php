<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use DB;

class PriceController extends Controller
{
    private function order_details_withID($order_id){
        $details = DB::table('ps_order_detail')
                 ->select('product_name as name','product_reference as barcode','product_quantity as quantity')
                 ->where('id_order',$order_id)->get();
        foreach($details as $detail){
            $detail->wholesale = Common::get_wholesale_price_by_ref($detail->barcode);
        }
        return $details;
    }
    public function partner_order_with_price_page(){
        $partners = DB::table('c1ft_stock_manager.sm_pos_partners')->get();
        return view('price.partner_order_with_price',compact('partners'));
    }

    public function partner_all_delivery(Request $request){
        $query = DB::table('c1ft_stock_manager.sm_pos_partners')->where('id',intval($request->partner_id));
        $customer_id = $query->value('ps_customer_id');
        $shop_id     = $query->value('rockpos_shop_id');
        $shop_name   = $query->value('name');
        $partner_orders  = [];
        $order_ids   = DB::table('ps_orders')
                     ->whereBetween('date_add',[$request->startTime,$request->endTime])
                     ->whereIn('current_state',[4,5])
                     ->where('id_shop',11)
                     ->pluck('id_order')->toArray();

        foreach($order_ids as $id){
            $partner_orders[] = [
                'order_ref'    => DB::table('ps_orders')->where('id_order',$id)->value('reference'),
                'date_add'     => DB::table('ps_orders')->where('id_order',$id)->value('date_add'),
                'order_detail' => self::order_details_withID($id),

            ];
        }

        $partner_reps = DB::table('c1ft_stock_manager.sm_all_replishment_history')
                       ->select('reference as barcode',DB::raw('sum(updated_quantity) as total_send'))
                       ->where('shop_id',intval($shop_id))
                       ->where('uploaded',1)
                       ->whereBetween('created_at',[$request->startTime,$request->endTime])
                       ->groupBy('reference')
                       ->get();

        foreach($partner_reps as $rep){
            $rep->name = Common::get_productName_by_ref($rep->barcode);
            $rep->wholesale = Common::get_wholesale_price_by_ref($rep->barcode);
        }

        return response()->json(['orders'=>$partner_orders,'reps'=>$partner_reps,'name'=>$shop_name,'from'=>$request->startTime,'to'=>$request->endTime]);
        // return $replishment;
        // return $partner_orders;
        // return self::order_details_withID(11712);
        // return $order_ids;
        //
        // return $customer_id;


    }
}
