<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use Nexmo\Laravel\Facade\Nexmo;
use DB;

class TrackStockController extends Controller
{
    public function trackStockByBrand(Request $request){
        //$query = DB::table('ps_product')->where('id_manufacturer',intval($request->manufactor_id))->pluck('reference')->toArray();
        $query = DB::table('ps_product')->where('id_manufacturer',intval($request->manufactor_id));
        $no_combination_refs = DB::table('ps_product')->where('id_manufacturer',intval($request->manufactor_id))->where('reference','!=','')->pluck('reference')->toArray();
        $combination_id      = DB::table('ps_product')->where('id_manufacturer',intval($request->manufactor_id))->where('reference','=','')->pluck('id_product')->toArray();
        $combination_refs    = DB::table('ps_product_attribute')->whereIn('id_product',$combination_id)->pluck('reference')->toArray();

        $allrefs = array_merge($no_combination_refs,$combination_refs);
        $lists = [];
        foreach($allrefs as $ref){
            $lists[]=[
                'name'         => Common::get_productName_by_ref($ref),
                'ref'          => $ref,
                'total_stockIn'=> Common::total_stockIn($ref,$request->from,$request->to),
                'total_send'   => Common::total_send($ref,$request->from,$request->to),
                'branch_sold'  => Common::productSoldQty_by_refInPos($ref,$request->from,$request->to),
                'online_order' => Common::product_onlineSold_by_ref($ref,$request->from,$request->to),
          'warehouse_standard' => Common::warehouse_standard($ref),
                'wholesale'    => Common::get_wholesale_price_by_ref($ref),
                'retail'       => Common::get_retail_price_by_ref($ref)
            ];
        }
        return $lists;
    }

    public function trackStockBy_singleProduct(Request $request){

        $searched_json = DB::select(
                        "select a.name,b.reference
                        from c1ft_pos_prestashop.ps_product_lang as a
                        join c1ft_pos_prestashop.ps_product as b
                        on a.id_product = b.id_product
                        where concat(b.reference,'',replace(a.name,' ','')) like '%$request->search%'
                        group by a.id_product
                        limit 1");
        $product = [];
        if(count($searched_json) == 1){
            $product_name = $searched_json[0]->name;
            $product_ref  = $searched_json[0]->reference;
            //return response()->json(['ada'=>$searched_json]);
            $product[]=[
                'name'        => $product_name,
                'reference'   => $product_ref,
                'stock_in'    => Common::total_stockIn($product_ref,$request->from,$request->to),
                'total_send'  => Common::total_send($product_ref,$request->from,$request->to),
                'store_sold'  => Common::productSoldQty_by_refInPos($product_ref,$request->from,$request->to),
                'online_order'=> Common::product_onlineSold_by_ref($product_ref,$request->from,$request->to),
         'warehouse_standard' => Common::warehouse_standard($product_ref),
                'wholesale'   => Common::get_wholesale_price_by_ref($product_ref),
                'retail'      => Common::get_retail_price_by_ref($product_ref)
            ];
            return response()->json(['product'=>$product,'pass'=>1]);
        }else{
            return response()->json(['pass'=>0]);
        }
    }

    public function trackStockBy_category(Request $request){

        $refs = [];
        $list = [];

        Nexmo::message()->send([
              'to'   => '00353873904445',
              'from' => 'stockMan',
              'text' => 'this is the test message hahahah.'
        ]);
        /*
            cate_id = 1 tempglass
            cate_id = 2 leather case
        */
        if(intval($request->cate_id) == 1){
            $refs = Common::temp_glassRefs();
        }else if(intval($request->cate_id) == 2){
            $refs = Common::leather_caseRefs();
        }

        if(count($refs) > 0){
            foreach($refs as $ref){
                $list[] = [
                    'name' => Common::get_productName_by_ref($ref),
                    'reference' => $ref,
                    'stock_in'  => Common::total_stockIn($ref,$request->from,$request->to),
                    'total_send'  => Common::total_send($ref,$request->from,$request->to),
                    'store_sold'  => Common::productSoldQty_by_refInPos($ref,$request->from,$request->to),
                    'online_order'=> Common::product_onlineSold_by_ref($ref,$request->from,$request->to),
             'warehouse_standard' => Common::warehouse_standard($ref),
                    'wholesale'   => Common::get_wholesale_price_by_ref($ref),
                    'retail'      => Common::get_retail_price_by_ref($ref)
                ];
            }
            return $list;
        }
    }


}
