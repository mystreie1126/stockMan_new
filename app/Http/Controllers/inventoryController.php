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
			$stockTake_record->name             = $request->name;
			$stockTake_record->reference        = $request->reference;
	    	$stockTake_record->updated_quantity = $request->qty;
			$stockTake_record->user_id          = $request->user_id;
			$stockTake_record->added            = $request->added;
		    $stockTake_record->created_at       = date('Y-m-d H:i:s');
		    $stockTake_record->save();

		    return 'saved';
		}

		public function myStockTake_records(Request $request){

			$query = DB::table('c1ft_stock_manager.sm_HQstockTake_history as a')
				   ->select('a.reference','a.name','a.updated_quantity','b.name as username','a.created_at','a.added')
				   ->join('c1ft_stock_manager.sm_users as b','a.user_id','b.id')
				   ->where('user_id',$request->user_id)
				   ->where('sealed',0)
				   ->get();

		    return $query;
		}

		public function allStockTake_records(){

			$query = DB::table('c1ft_stock_manager.sm_HQstockTake_history as a')
				   ->select('a.reference','a.name','a.updated_quantity','b.name as username','a.created_at','a.added')
				   ->join('c1ft_stock_manager.sm_users as b','a.user_id','b.id')
				   ->where('sealed',0)
				   ->get();;

		    return $query;
		}

		public function stockTake_final_results(){

			$query = DB::table('c1ft_stock_manager.sm_HQstockTake_history')
			       ->select('name','reference',DB::raw('sum(updated_quantity) as total_updated'))
				   ->groupBy('reference')
				   ->get();
		    return $query;

		}

}
