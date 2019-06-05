<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use DB;


class kerianController extends Controller
{
    private function get_productSoldQty_by_ref_allshops($ref,$from,$to){

        $pos_qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                  ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                  ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
                  ->whereBetween('order.date_add',[$from,$to])
                  ->where('detail.product_reference',$ref)
                  ->groupBy('detail.product_reference')
                  ->value('soldQty');

        if(in_array($ref,Common::allCombinationRefs())){

            $web_qty = DB::table('ps_product_attribute as attr')
                        ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                        ->where('attr.reference',$ref)
                        ->join('ps_order_detail as detail','attr.id_product_attribute','detail.product_attribute_id')
                        ->groupBy('detail.product_attribute_id')
                        ->join('vr_confirm_payment as webSales','webSales.order_id','detail.id_order')
                        ->where('webSales.device_order',0)
                        ->whereBetween('webSales.created_at',[$from,$to])
                        ->value('soldQty');

            return intval($web_qty) + intval($pos_qty);

        }else if(!in_array($ref,Common::allCombinationRefs()) && in_array($ref,Common::allExcludeCombinationRefs())){

            $web_qty = DB::table('ps_order_detail as detail')
                       ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                       ->join('vr_confirm_payment as webSales','webSales.order_id','detail.id_order')
                       ->where('webSales.device_order',0)
                       ->whereBetween('webSales.created_at',[$from,$to])
                       ->where('detail.product_reference',$ref)
                       ->value('soldQty');
            return intval($web_qty) + intval($pos_qty);

        }else{
            return 0;
        }
    }


    private function posSalesRefs_allshops($from,$to){
    $query =  DB::connection('mysql2')->table('ps_order_detail as sales')
         ->select('sales.product_id as SALE_productID','pos.reference')
         ->join('ps_product as pos','sales.product_id','pos.id_product')
         ->join('ps_orders','ps_orders.id_order','sales.id_order')
         ->whereBetween('ps_orders.date_add',[$from,$to])
         //->where('sales.id_shop',$shop_id)
         ->where('pos.reference','!=','EG-PRODUCT01')
         ->groupBy('pos.reference')
         ->orderBy('pos.reference')
         ->pluck('pos.reference')->toArray();

         return $query;
     }

     private function webSalesRefs_allshops($from,$to){
        $query = DB::table('vr_confirm_payment as webSales')
                 ->select('detail.product_reference')
                 ->join('ps_order_detail as detail','detail.id_order','webSales.order_id')
                 ->whereBetween('webSales.created_at',[$from,$to])
                 ->where('webSales.device_order',0)
                 //->where('webSales.rockpos_shop_id',$shop_id)
                 ->groupBy('detail.product_reference')
                 ->pluck('detail.product_reference')->toArray();
        return $query;
     }

     private function get_sales_refs($from,$to){
         $missing_refs = Common::missingPart(self::posSalesRefs_allshops($from,$to),self::webSalesRefs_allshops($from,$to));
         $sold_refs = array_merge(self::posSalesRefs_allshops($from,$to),$missing_refs);

         return $sold_refs;
     }

    public function allShopSalesQty_by_ref(){
        $from = '2019-05-27 00:00:00';
        $to   = '2019-06-02 23:00:00';
        $shop_id = 27;

        $pos_sale_refs  = Common::totalSalesRefs($from,$to,$shop_id);
        $web_sales_refs = Common::webSalesRefs($from,$to,$shop_id);
        $missing_refs = Common::missingPart($pos_sale_refs,$web_sales_refs);

        $sell_refs = array_merge($pos_sale_refs,$missing_refs);

        $result = [];


        foreach($sell_refs as $r){
            $result[] = [
                'name' => Common::get_productName_by_ref($r),
                'reference'=> $r,
                'sold_qty' => Common::get_productSoldQty_by_ref($r,$shop_id,$from,$to)
            ];
        }

        return $result;

    }

    public function athlone_standard_list(){
        $branchStockTake_refs = DB::table('c1ft_stock_manager.sm_branchStockTake_history')
                 ->where('sealed',1)
                 ->where('shop_id',27)
                 ->groupBy('reference')->pluck('reference')->toArray();
        $arr = [];
        $invalidArr = [];
        $allrefs =  array_merge(Common::extraRefsAfterStockTake(27),$branchStockTake_refs);

        foreach($allrefs as $ref){

                $arr[] = [
                    'name' => Common::get_productName_by_ref($ref),
                    'webStockID' => Common::get_webStockID_by_ref($ref),
                    'branchStockID' => Common::get_branchStockID_by_ref($ref,27),
                    'reference' => $ref
                ];

        }

        return $arr;

    }

    public function product_without_imageSold(){
        $query = DB::table('c1ft_stock_manager.product_without_image')->get();
        $from = '2019-03-01 00:00:00';
        $to = date('Y-m-d h:i:s');
        foreach($query as $q){
            DB::table('c1ft_stock_manager.product_without_image')
            ->where('ref',$q->ref)
            ->update(['sold_qty'=>self::get_productSoldQty_by_ref_allshops($q->ref,$from,$to)]);
        }

        return 'done';
    }




}
