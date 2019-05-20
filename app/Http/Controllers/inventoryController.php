<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Common;
use App\Model\StockTake\HQ_stockTake as stockTake_record;
use Facades\App\Repository\StockTake;
// use Illuminate\Support\Facades\Auth;


class inventoryController extends Controller
{

		public function HQ_invetory_list(){

			return StockTake::HQ_stockTake();
		}

		public function test(){
			return stockTake_record::all();
		}

		public function saveTo_stockTakeHistory(Request $request){

			$stockTake_record = new stockTake_record;

			$stockTake_record->web_stock_id     = $request->web_stock_id;
			$stockTake_record->reference        =	$request->reference;
	    	$stockTake_record->current_quantity = $request->qty;
			$stockTake_record->user_id          = $request->user_id;
		    $stockTake_record->created_at       = date('Y-m-d H:i:s');
		    $stockTake_record->save();

		    return 'saved';
		}

		public function myStockTake_records(Request $request){

			$query = DB::table('c1ft_stock_manager.sm_hqInventoryCountHistory as a')
					 ->select('a.reference','c.name','a.current_quantity','user.name as user','a.created_at')
					 ->join('c1ft_pos_prestashop.ps_product as b','a.reference','b.reference')
					 ->join('c1ft_pos_prestashop.ps_product_lang as c','b.id_product','c.id_product')
					 ->groupBy('c.name')
					 ->join('c1ft_stock_manager.sm_users as user','a.user_id','user.id')
					 ->orderBy('a.created_at','desc')
					 ->get();

		    return $query;

			return $request->user_id;

		}




}
