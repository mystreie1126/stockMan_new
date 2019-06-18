<?php

namespace App\Helper;
use DB;

class Common
{
    public static $get_productSoldQty_by_ref_value;


    public static $shops = ['Athlone','Arthus Quay','Blackpool','Douglas','Galway Shop','Gorey','MarketCross','Parkway','Wexford','Millfield','Blackpool','Douglas'];
    public static function missingPart($targetArr,$loopArr){
      $arr = [];
      for($i = 0; $i < count($loopArr); $i++){
         if(!in_array($loopArr[$i],$targetArr)) array_push($arr,$loopArr[$i]);
      }
      return $arr;
   }

    public static function ifhasBranchStock($pos_stock_id){
        $has_branchStock_id = DB::table('c1ft_stock_manager.sm_updateStockRecord')->pluck('stock_id')->toArray();

        return in_array($pos_stock_id,$has_branchStock_id);
    }

    /* ===================================helper refs=============================================== */

    public static function totalSalesRefs_allshops(){
	    $query =  DB::connection('mysql2')->table('ps_order_detail as sales')
	         ->select('sales.product_id as SALE_productID','pos.reference')
	         ->join('ps_product as pos','sales.product_id','pos.id_product')
	         ->join('ps_orders','ps_orders.id_order','sales.id_order')
	         ->whereBetween('ps_orders.date_add',['2019-05-01','2019-05-20'])
	         //->where('sales.id_shop',$shop_id)
	         ->where('pos.reference','!=','EG-PRODUCT01')
             ->whereBetween('ps_orders.date_add',['2019-05-01','2019-05-20'])
	         ->groupBy('pos.reference')
	         ->orderBy('pos.reference')
	         ->pluck('pos.reference')->toArray();

	         return $query;
    }

    public static function webSalesRefs_allshops(){
    	$query = DB::table('vr_confirm_payment as webSales')
    	         ->select('detail.product_reference')
    	         ->join('ps_order_detail as detail','detail.id_order','webSales.order_id')
    	         ->whereBetween('webSales.created_at',['2019-05-01','2019-05-20'])
    	         //->where('webSales.rockpos_shop_id',$shop_id)
    	         ->groupBy('detail.product_reference')
    	         ->pluck('detail.product_reference')->toArray();
    	return $query;
    }

    public static function updated_record_refs_allShops(){
    	$query = DB::table('c1ft_stock_manager.sm_updateStockRecord as record')
    		   ->select('record.reference as ref')
    		   ->groupBy('reference')
    		   ->pluck('ref')->toArray();

    	return $query;
    }

    public static function standardQty($id_product){
        $query = DB::table('c1ft_stock_manager.sm_pos_product_standard')
                 ->select('standard')
                 ->where('pos_product_id',$id_product)
                 ->value('standard');
        return intval($query);
    }


 /* ===================================References=============================================== */

   //  public static function sharedSalesRefs($startDate,$endDate,$shop_id){
   //    $query =  DB::connection('mysql2')->table('ps_order_detail as sales')
   //       ->select('sales.product_id as SALE_productID','pos.reference','web.id_product as WEB_productID')
   //       ->join('ps_product as pos','sales.product_id','pos.id_product')
   //       ->join('c1ft_store_prestashop.ps_product as web','web.reference','pos.reference')
   //       ->join('ps_orders','ps_orders.id_order','sales.id_order')
   //       ->whereBetween('ps_orders.date_add',[$startDate,$endDate])
   //       ->where('sales.id_shop',$shop_id)
   //       ->where('sales.product_id','!=',853)
   //       ->whereNotIn('pos.reference',['EG-PRODUCT01',''])
   //       ->groupBy('pos.reference')
   //       ->orderBy('pos.reference')
   //       ->pluck('pos.reference')->toArray();

   //       return $query;
   // }

   	public static function totalSalesRefs($startDate,$endDate,$shop_id){
	    $query =  DB::connection('mysql2')->table('ps_order_detail as sales')
	         ->select('sales.product_id as SALE_productID','pos.reference')
	         ->join('ps_product as pos','sales.product_id','pos.id_product')
	         ->join('ps_orders','ps_orders.id_order','sales.id_order')
	         ->whereBetween('ps_orders.date_add',[$startDate,$endDate])
	         ->where('sales.id_shop',$shop_id)
	         ->where('pos.reference','!=','EG-PRODUCT01')
	         ->groupBy('pos.reference')
	         ->orderBy('pos.reference')
	         ->pluck('pos.reference')->toArray();

	         return $query;
    }

    public static function webSalesRefs($startDate,$endDate,$shop_id){
    	$query = DB::table('vr_confirm_payment as webSales')
    	         ->select('detail.product_reference')
    	         ->join('ps_order_detail as detail','detail.id_order','webSales.order_id')
    	         ->whereBetween('webSales.created_at',[$startDate,$endDate])
    	         ->where('webSales.rockpos_shop_id',$shop_id)
    	         ->groupBy('detail.product_reference')
    	         ->pluck('detail.product_reference')->toArray();
    	return $query;
    }

    public static function allCombinationRefs(){
        $query = DB::table('ps_product_attribute')
                ->select('reference')->groupBy('reference')
                ->whereNotIn('reference',['','Mill'])
                ->where('reference','not like','%'.'AL'.'%')
                ->pluck('reference')->toArray();
        return $query;
    }

    public static function allExcludeCombinationRefs(){
        $query = DB::table('ps_product')
               ->where('reference','!=','')
               ->where('reference','not like','AL'.'%')
               ->where('reference','not like','%'.'unlock'.'%')
               ->groupBy('reference')->pluck('reference')->toArray();
        return $query;
    }

    public static function updated_record_refs($shop_id){
    	$query = DB::table('c1ft_stock_manager.sm_updateStockRecord as record')
    		   ->select('record.reference as ref')
    		   ->where('record.shop_id',$shop_id)
    		   ->join('c1ft_stock_manager.sm_pos_product_standard as standard','record.id_product','standard.pos_product_id')
    		   ->join('c1ft_pos_prestashop.ps_stock_available as stock','stock.id_stock_available','record.stock_id')
    		   ->where(DB::raw('standard.standard - stock.quantity'),'>=',0)
    		   ->pluck('ref')->toArray();

    	return $query;
    }

    public static function hq_inventory_list(){

    	$normal = DB::table('ps_stock_available as a')
    		 ->select('p.reference as ref','a.id_stock_available as stock_id','a.quantity')
    		 ->where('a.id_product_attribute',0)
    		 ->where('p.reference','!=','')
    		 ->whereNotIn('p.reference',['Athlone','Arthus Quay','Blackpool','Douglas','Galway Shop','Gorey','MarketCross','Parkway','Wexford','Millfield','Blackpool','Douglas','Mill','ClareHall','Ashleaf','Galway','Arthursquay','ArthrusQuay','Arthurs Quay','Athone','Market Cross'])
    		  ->where('p.reference','not like','%'.'al'.'%')
    		  //->where('p.reference','not like','%'.'90'.'%')
    		  ->where('p.reference','not like','%'.'unlock'.'%')
    		  //->where('p.reference','not like','%'.'70'.'%')
    	    ->join('ps_product as p','a.id_product','p.id_product');

    	$all = DB::table('ps_stock_available as a')
    		->select('attr.reference as ref','a.id_stock_available as stock_id','a.quantity')
    		->where('a.id_product_attribute','!=',0)
    		->whereNotIn('attr.reference',['Mill','','Arthurs Quay'])
    		->join('ps_product_attribute as attr','attr.id_product_attribute','a.id_product_attribute')
        ->union($normal)
    		->get();

    	// $test = DB::table('ps_stock_available as a')
    	// 	->select('attr.reference as ref','a.id_stock_available as stock_id')
    	// 	->where('a.id_product_attribute','!=',0)
    	// 	->whereNotIn('attr.reference',['Mill','','Arthurs Quay'])
    	// 	->join('ps_product_attribute as attr','attr.id_product_attribute','a.id_product_attribute')
    	// 	->where('attr.reference','>',300025)
    	// 	->get();

        return $all;
        //return $test;

    }


/*===================================================================================================================== */


/* ===================================for replishment list array================================== */

	//1.get SINGLE sold qty by ref
    public static function get_productSoldQty_by_ref($ref,$shop_id,$from,$to){

        $pos_qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                  ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                  ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
                  ->whereBetween('order.date_add',[$from,$to])
                  ->where('detail.id_shop',$shop_id)
                  ->where('detail.product_reference',$ref)
                  ->groupBy('detail.product_reference')
                  ->value('soldQty');

        if(in_array($ref,self::allCombinationRefs())){

            $web_qty = DB::table('ps_product_attribute as attr')
                        ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                        ->where('attr.reference',$ref)
                        ->join('ps_order_detail as detail','attr.id_product_attribute','detail.product_attribute_id')
                        ->groupBy('detail.product_attribute_id')
                        ->join('vr_confirm_payment as webSales','webSales.order_id','detail.id_order')
                        ->where('webSales.device_order',0)
                        ->where('webSales.rockpos_shop_id',$shop_id)
                        ->whereBetween('webSales.created_at',[$from,$to])
                        ->value('soldQty');

            return intval($web_qty) + intval($pos_qty);

        }else if(!in_array($ref,self::allCombinationRefs()) && in_array($ref,self::allExcludeCombinationRefs())){

            $web_qty = DB::table('ps_order_detail as detail')
                       ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                       ->join('vr_confirm_payment as webSales','webSales.order_id','detail.id_order')
                       ->where('webSales.device_order',0)
                       ->where('webSales.rockpos_shop_id',$shop_id)
                       ->whereBetween('webSales.created_at',[$from,$to])
                       ->where('detail.product_reference',$ref)
                       ->value('soldQty');
            return intval($web_qty) + intval($pos_qty);

        }else{
            return 0;
        }
    }

    //2.get SINGLE web stock id by ref

    public static function get_webStockID_by_ref($ref){
        if(in_array($ref,self::allCombinationRefs())){
            $query = DB::table('ps_product_attribute as attr')
                ->select('stock.id_stock_available')
                ->join('ps_stock_available as stock','stock.id_product_attribute','attr.id_product_attribute')
                ->where('attr.reference',$ref)
                ->get();

                if($query->count() == 1)return $query[0]->id_stock_available;
            }else{
                $query = DB::table('ps_product')
                    ->select('stock.id_stock_available')
                    ->join('ps_stock_available as stock','stock.id_product','ps_product.id_product')
                    ->where('ps_product.reference',$ref)
                    ->get();

                if($query->count() == 1) return $query[0]->id_stock_available;
        }
    }

    //3. get SINGLE pos stock id by ref
    public static function get_branchStockID_by_ref($ref,$shop_id){
        $query = DB::table('c1ft_pos_prestashop.ps_product as product')
            ->select('stock.id_stock_available')
            ->join('c1ft_pos_prestashop.ps_stock_available as stock','stock.id_product','product.id_product')
            ->where('stock.id_shop',$shop_id)
            ->where('product.reference',$ref)
            ->get();

        if($query->count() == 1) return $query[0]->id_stock_available;
    }

    //4. get SINGLE product name by ref(rockpos)
    public static function get_productName_by_ref($ref){
        $query = DB::table('c1ft_pos_prestashop.ps_product_lang as name')
            ->select('name.name')
            ->join('c1ft_pos_prestashop.ps_product as product','name.id_product','product.id_product')
            ->where('product.reference',$ref)
            ->groupBy('name.name')
            ->get();

        if($query->count() == 1) return $query[0]->name;
    }

    //5.get SINGLE standard qty by ref
    public static function get_productStandard_by_ref($ref){
        $query = DB::table('c1ft_stock_manager.sm_pos_product_standard as a')
                ->select('a.standard')
                ->join('c1ft_pos_prestashop.ps_product as p','a.pos_product_id','p.id_product')
                ->where('p.reference',$ref)
                ->get();

        if($query->count() ==1) return intval($query[0]->standard);
    }


    //6.get SINGLE branch stock qty by ref

    public static function get_branchStockQty_by_ref($ref,$shop_id){
        $qty = DB::table('c1ft_pos_prestashop.ps_stock_available as stock')
               ->select('stock.quantity')
               ->join('c1ft_pos_prestashop.ps_product as p','stock.id_product','p.id_product')
               ->where('p.reference',$ref)
               ->where('stock.id_shop',$shop_id)
               ->get();
        if($qty->count() == 1) return intval($qty[0]->quantity);

    }


    public static function get_branch_name_by_shopID($shop_id){
        $name= DB::table('c1ft_pos_prestashop.ps_shop')
                ->where('id_shop',$shop_id)
                ->value('name');
        return $name;
    }

    //7.get stock_in record by refs

    public static function get_product_deliveredQty_to_Branch($ref,$shop_id,$from,$to){
        $qty = DB::table('c1ft_stock_manager.sm_all_replishment_history')
               ->select(DB::raw('sum(updated_quantity) as sendQty'))
               ->where('uploaded',1)
               ->where('shop_id',$shop_id)
               ->where('reference',$ref)
               ->whereBetween('created_at',[$from,$to])
               ->groupBy('reference')
               ->get();

           if($qty->count() == 1) {
               return intval($qty[0]->sendQty);
           }else{
               return 0;
           }
    }

    //8.get product catagory type by ref


    public static function get_qty_lastStockTake($ref,$shop_id){
        $qty = DB::table('c1ft_stock_manager.sm_branchStockTake_history')

               ->where('shop_id',$shop_id)
               ->where('sealed',1)
               ->where('reference',$ref)
               ->groupBy('reference')->value(DB::raw('sum(updated_quantity)'));

               return intval($qty);
    }


    /*============================================================================================== */

    public static function extraRefsAfterStockTake($shop_id){
        $branchStockTake_refs = DB::table('c1ft_stock_manager.sm_branchStockTake_history')
                 ->where('sealed',1)
                 ->where('shop_id',$shop_id)
                 ->groupBy('reference')->pluck('reference')->toArray();

        $HQ_stockTake_refs = DB::table('c1ft_stock_manager.sm_HQstockTake_history')
                 ->where('sealed',1)
                 ->groupBy('reference')->pluck('reference')->toArray();

        $a =  Common::missingPart($branchStockTake_refs,$HQ_stockTake_refs);

        return array_merge($a,$branchStockTake_refs);
    }

    public static function get_productSoldQty_by_ref_allShop($ref,$from,$to){

        $pos_qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
                  ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
                  ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
                  ->whereBetween('order.date_add',[$from,$to])

                  ->where('detail.product_reference',$ref)
                  ->groupBy('detail.product_reference')
                  ->value('soldQty');

        if(in_array($ref,self::allCombinationRefs())){

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

        }else if(!in_array($ref,self::allCombinationRefs()) && in_array($ref,self::allExcludeCombinationRefs())){

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

}
