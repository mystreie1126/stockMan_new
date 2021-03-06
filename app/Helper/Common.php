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
    	         ->where('webSales.rockpos_shop_id',$shop_id)
                 ->whereBetween('webSales.created_at',[$startDate,$endDate])
    	         ->join('ps_order_detail as detail','detail.id_order','webSales.order_id')
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

        }else if(!in_array($ref,self::allCombinationRefs())){

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

        if($query->count() == 1) {
            return $query[0]->id_stock_available;
        }else{
            return 0;
        }
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
        if($qty->count() == 1) {
            return intval($qty[0]->quantity);
        }else{
            return 0;
        }

    }


    public static function get_branch_name_by_shopID($shop_id){
        $name= DB::table('c1ft_pos_prestashop.ps_shop')
                ->where('id_shop',$shop_id)
                ->value('name');
        return $name;
    }

    public static function standard_refs($shop_id){
        $refs = DB::table('c1ft_stock_manager.sm_standard_branch')->where('shop_id',$shop_id)->pluck('reference')->toArray();
        return $refs;
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

    public static function get_standardQty_by_ref($ref,$shop_id){
        $standard = DB::table('c1ft_stock_manager.sm_standard_branch')
                 ->where('reference',$ref)
                 ->where('shop_id',$shop_id)
                 ->value('standard_quantity');
        if($standard){
            return intval($standard);
        }

    }


    public static function branch_product_qty_by_ref($ref,$shop_id){
        $qty = DB::table('c1ft_pos_prestashop.ps_product as a')
                ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                ->where('b.id_shop',$shop_id)
                ->where('a.reference',$ref)
                ->value('b.quantity');
        return intval($qty);

    }

    //9.get product price by ref

    public static function get_retail_price_by_ref($ref){

            $price = DB::table('c1ft_pos_prestashop.ps_product')
                     ->where('reference',$ref)
                     ->value('price');

            return number_format($price, 2);

        //$price = DB::table('c1ft_pos_prestashop.ps_product')->where('reference',$ref)->value('price');

    }

    public static function get_cost_price_by_ref($ref){
        if(in_array($ref,self::allCombinationRefs())){
            $price = DB::table('ps_product_attribute as a')
                     ->join('ps_product as b','a.id_product,b.id_product')
                     ->where('a.reference',$ref)
                     ->value('b.wholesale_price');
            return number_format($price, 2);

        }else if(!in_array($ref,self::allCombinationRefs())){
            $price = DB::table('ps_product')->where('reference',$ref)->value('wholesale_price');
            return number_format($price, 2);

        }else{
            return 0;
        }

    }

    public static function get_wholesale_price_by_ref($ref){
        if(in_array($ref,self::allCombinationRefs()) && !in_array($ref,self::allExcludeCombinationRefs())){
            $price = DB::table('ps_product_attribute as a')
                   ->select('b.price as retail','a.price as impact','c.reduction')
                   ->where('a.reference',$ref)
                   ->join('ps_product as b','a.id_product','b.id_product')
                   ->join('ps_specific_price as c','c.id_product','a.id_product')
                   ->where('c.id_shop',11)
                   ->where('c.id_group',5)
                   ->get();

                   if($price->count() == 1){
                       return number_format(floatval($price[0]->retail) + floatval($price[0]->impact) - floatval($price[0]->reduction),2);
                   }else{
                       return 0;
                   }

        }else if(!in_array($ref,self::allCombinationRefs()) && in_array($ref,self::allExcludeCombinationRefs())){
              $price = DB::table('ps_product as a')
                    ->select('a.price','b.reduction',DB::raw('(a.price-b.reduction) as wholesale') )
                    ->where('a.reference',$ref)
                    ->join('ps_specific_price as b','a.id_product','b.id_product')
                    ->where('b.id_shop',11)
                    ->where('b.id_group',5)
                    ->get();

                    if($price->count() == 1){
                        return number_format(floatval($price[0]->wholesale),2);
                    }else{
                        return 0;
                    }
        }else{
            return 0;
        }

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

    public static function productSoldQty_by_refInPos($ref,$from,$to){
        $qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
              ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
              ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
              ->whereBetween('order.date_add',[$from,$to])
              ->where('detail.product_reference',$ref)
              ->groupBy('detail.product_reference')
              ->value('soldQty');
       return intval($qty);
    }

    public static function productSoldQty_by_refInPos_shop($ref,$shop,$from,$to){
        $qty = DB::table('c1ft_pos_prestashop.ps_order_detail as detail')
              ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
              ->join('c1ft_pos_prestashop.ps_orders as order','order.id_order','detail.id_order')
              ->whereBetween('order.date_add',[$from,$to])
              ->where('detail.product_reference',$ref)
              ->where('detail.id_shop',$shop)
              ->groupBy('detail.product_reference')
              ->value('soldQty');
       return intval($qty);
    }
    public static function total_stockIn($ref,$from,$to){
        $qty = DB::table('c1ft_stock_manager.sm_stock_in_history')
               ->select(DB::raw('sum(quantity) as qty'))
               ->whereBetween('created_at',[$from,$to])
               ->where('reference',$ref)
               ->groupBy('reference')
               ->value('qty');
       return intval($qty);
    }

    public static function total_send($ref,$from,$to){
        $qty  = DB::table('c1ft_stock_manager.sm_all_replishment_history')
               ->select(DB::raw('sum(updated_quantity) as qty'))
               ->whereBetween('created_at',[$from,$to])
               ->where('reference',$ref)
               ->groupBy('reference')
               ->value('qty');

        $order_qty = DB::table('c1ft_store_prestashop.ps_order_detail as detail')
              ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
              ->join('c1ft_store_prestashop.ps_orders as order','order.id_order','detail.id_order')
              ->whereBetween('order.date_add',[$from,$to])
              ->whereIn('order.current_state',[4,5])
              ->where('detail.product_reference',$ref)
              ->where('order.id_shop',11)
              ->groupBy('detail.product_reference')
              ->value('soldQty');

        return intval($qty) + intval($order_qty);
    }

    public static function product_onlineSold_by_ref($ref,$from,$to){
        $qty = DB::table('ps_order_detail as detail')
              ->select(DB::raw('sum(detail.product_quantity) as soldQty'))
              ->join('ps_orders as order','order.id_order','detail.id_order')
              ->whereBetween('order.date_add',[$from,$to])
              ->whereIn('order.current_state',[2,4,5,9,30,26,36,35,34,29,25])
              ->where('detail.product_reference',$ref)
              ->groupBy('detail.product_reference')
              ->value('soldQty');
       return intval($qty);
    }

    public static function temp_glassRefs(){

        $refs = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                ->join('c1ft_pos_prestashop.ps_product as b','a.id_product','b.id_product')
                ->select('b.reference')
                ->where('a.name','like','%'.'glass'.'%')
                ->where('a.name','like','%'.'temp'.'%')
                ->where('b.reference','<>','')
                ->groupBy('a.name')
                ->pluck('reference')->toArray();
        return $refs;
    }

    public static function leather_caseRefs(){
        $refs = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                ->join('c1ft_pos_prestashop.ps_product as b','a.id_product','b.id_product')
                ->select('b.reference')
                ->where('a.name','like','%'.'leather'.'%')
                ->where('a.name','like','%'.'case'.'%')
                ->where('b.reference','<>','')
                ->where('b.reference','not like','6'.'%')
                ->groupBy('a.name')
                ->orderBy('b.reference','desc')
                ->pluck('reference')->toArray();
        return array_unique($refs);
    }

    public static function gel_caseRefs(){
        $refs = DB::table('ps_product_lang as a')
                ->join('ps_product as b','a.id_product','b.id_product')
                ->select('b.reference')
                ->where('a.name','like','%'.'gel'.'%')
                ->orWhere('a.name','like','%'.'auto focus'.'%')
                ->orWhere('a.name','like','%'.'solid invisible'.'%')
                ->Where('b.reference','<>','')
                ->groupBy('a.name')
                ->orderBy('b.reference','desc')
                ->pluck('reference')->toArray();
        return array_unique($refs);
    }

    public static function shockproof_caseRefs(){
        $refs = DB::table('ps_product_lang as a')
                ->join('ps_product as b','a.id_product','b.id_product')
                ->select('b.reference')
                ->where('a.name','like','%'.'shockproof'.'%')
                ->orWhere('a.name','like','%'.'commuter'.'%')
                ->orWhere('a.name','like','%'.'defeneder'.'%')
                ->Where('b.reference','<>','')
                ->groupBy('a.name')
                ->orderBy('b.reference','desc')
                ->pluck('reference')->toArray();
        return array_unique($refs);
    }


    public static function usams_refs(){
        $refs = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                ->join('c1ft_pos_prestashop.ps_product as b','a.id_product','b.id_product')
                ->select('b.reference')
                ->where('a.name','like','%'.'usams'.'%')
                ->groupBy('a.name')
                ->pluck('reference')->toArray();
        return $refs;
    }

    public static function xiaomi_refs(){
        // $refs1 = DB::table('ps_product')
        //         ->select('id_product','reference')
        //         ->where('id_manufacturer',332);
        //         ->whereNotIn('reference',['','Mill',32879182743])
        //         ->pluck('reference')->toArray();
        //
        // $refs2 = DB::table('ps_product')->where('id_manufacturer',332)->get();
        // return $refs2;
        // return $ids;
        // $q  =DB::table('ps_product_attribute')->select('reference')->whereIn('id_product',$ids)->get();
        // return $q;
        // return $refs1;
        //         return $query;

    }

    public static function warehouse_Standard_qty_formular($send_eachWeek,$stock_arrival_period){
        /*
        $send_eachWeek = 5 weeks total send /5
        $stock_arrival_period :
                (temp glass   = 2 weeks
                leather case = 5 weeks
                usams = 3 weeks
                other = 2 weeks)
        */
        // self::total_send($ref,$from,$to)
        return intval($send_eachWeek * $stock_arrival_period);
    }

    public static function warehouse_standard($ref){
        // with 5 weeks period
        $five_weeks_before = date('Y-m-d H:i:s',strtotime("-5 week"));
        $now = date('Y-m-d H:i:s');
        if(in_array($ref,self::usams_refs())){
            //usams
            return round(self::total_send($ref,$five_weeks_before,$now)/5) * 3;

        }else if(in_array($ref,self::temp_glassRefs())){
            //temp glass
            return round(self::total_send($ref,$five_weeks_before,$now)/5) * 2;

        }else if(in_array($ref,self::leather_caseRefs())){
            //leather cases
            return round(self::total_send($ref,$five_weeks_before,$now)/5) * 5;

        }else{
            //all other products
            return round(self::total_send($ref,$five_weeks_before,$now)/5) * 2;
        }
    }

    public static function get_webStock_qty_by_ref($ref){
        if(in_array($ref,self::allCombinationRefs())){
            $query = DB::table('ps_product_attribute as attr')
                ->join('ps_stock_available as stock','stock.id_product_attribute','attr.id_product_attribute')
                ->where('attr.reference',$ref)
                ->value('stock.quantity');

                return intval($query);

                // if($query->count() == 1){
                //     return $query[0]->quantity;
                // }else

            }else{
                $query = DB::table('ps_product')
                    ->join('ps_stock_available as stock','stock.id_product','ps_product.id_product')
                    ->where('ps_product.reference',$ref)
                    ->value('stock.quantity');
                return intval($query);

                //if($query->count() == 1) return $query[0]->id_stock_available;
        }
    }

    public static function shopemail($shop_id){
        $email = DB::table('c1ft_stock_manager.sm_shop_email')->where('shop_id',$shop_id)->value('shop_mail');
        return $email;
    }

    public static function shopname($shop_id){
        $shopname = DB::table('c1ft_pos_prestashop.ps_shop')->where('id_shop',$shop_id)->value('name');
        return $shopname;
    }


    public static function checkdeviceInPos_inStock($imei,$shop_id){

        $inStock = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                 ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                 ->join('c1ft_pos_prestashop.ps_product_shop as c','c.id_product','a.id_product')
                 ->join('c1ft_pos_prestashop.ps_product as d','a.id_product','d.id_product')
                 ->where('a.id_shop',$shop_id)
                 ->where('b.id_shop',$shop_id)
                 ->where('c.id_shop',$shop_id)
                 ->where('d.reference','like','%'.$imei.'%')
                 ->where('c.active',1)
                 ->where('b.quantity',1)
                 ->get();

        return $inStock->count();
        //  $in_rockpos     = '';
        //  $ready_for_sell = ''; //quantity && active
        //
        //  if($inStock->get()->count() == 1){
        //      $in_rockpos = 'exsist in rockpos';
        //  }else{
        //      $in_rockpos = 'not in rockpos';
        //  }
        //
        //  if($inStock->where('c.active',1)->get()->count() == 1){
        //      $in_rockpos = 'exsist in rockpos';
        //     if($inStock->where('b.quantity',1)->get()->count() == 1){
        //         $ready_for_sell = 'ready to sell';
        //     }else if($inStock->where('b.quantity',1)->get()->count() == 0){
        //         $ready_for_sell = 'had been sold before';
        //     }
        // }else if($inStock->where('c.active',0)->get()->count() == 1){
        //      $in_rockpos = 'exsist in rockpos';
        //      $ready_for_sell  = ' not avaialbe to sell';
        // }
        //
        // return $in_rockpos.' '.$ready_for_sell;

    }

    public static function checkdeviceInPos_sold($imei,$shop_id){

        $query = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                 ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                 ->join('c1ft_pos_prestashop.ps_product_shop as c','c.id_product','a.id_product')
                 ->join('c1ft_pos_prestashop.ps_product as d','a.id_product','d.id_product')
                 ->where('a.id_shop',$shop_id)
                 ->where('b.id_shop',$shop_id)
                 ->where('c.id_shop',$shop_id)
                 ->where('d.reference','like','%'.$imei.'%')
                 ->where('b.quantity',0)
                 ->get();

        return $query->count();
    }

    public static function checkdeviceInPos_active($imei,$shop_id,$time){

        $query = DB::table('c1ft_pos_prestashop.ps_product_lang as a')
                 ->join('c1ft_pos_prestashop.ps_stock_available as b','a.id_product','b.id_product')
                 ->join('c1ft_pos_prestashop.ps_product_shop as c','c.id_product','a.id_product')
                 ->join('c1ft_pos_prestashop.ps_product as d','a.id_product','d.id_product')
                 ->where('a.id_shop',$shop_id)
                 ->where('b.id_shop',$shop_id)
                 ->where('c.id_shop',$shop_id)
                 ->where('d.reference','like','%'.$imei.'%')
                 ->where('c.active',0)
                 ->get();

        $sold = DB::table('c1ft_pos_prestashop.ps_orders as a')
                ->join('c1ft_pos_prestashop.ps_order_detail as b','a.id_order','b.id_order')
                ->where('b.product_reference',$imei)
                ->where('b.id_shop',$shop_id)
                ->where('a.date_add','>=',$time)
                ->value('b.product_quantity');

        if($query->count() == 1){
           if($query[0]->quantity == 1){
               return 1;
           }else if($query[0]->quantity == 0 && intval($sold) == 1){
               return 1;
           }else{
               return 0;
           }
        }else{
            return 0;
        }
    }

    public static function checkPartsQty_ifMatchInPos($parts_id,$shop_id,$sheet_qty,$time){
        $pos_qty = DB::table('c1ft_pos_prestashop.ps_stock_available')
                ->where('id_shop',$shop_id)
                ->where('id_product',$parts_id)
                ->value('quantity');

        $sold = DB::table('c1ft_pos_prestashop.ps_orders as a')
                ->join('c1ft_pos_prestashop.ps_order_detail as b','a.id_order','b.id_order')
                ->select(DB::raw('b.product_quantity as qty'))
                ->where('b.product_id',$parts_id)
                ->where('b.id_shop',$shop_id)
                ->where('a.date_add','>=',$time)
                ->groupBy('b.product_reference')
                ->get();
        // if(intval($pos_qty) == intval($sheet_qty)) 
        
        return (intval($pos_qty) + intval($sold[0]->qty)) === intval($sheet_qty) ? 1 : 0;
    }


}
