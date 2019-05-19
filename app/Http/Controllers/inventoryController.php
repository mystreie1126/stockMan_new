<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Common;
use App\Model\Inventory\hqInventory_history as InvHistory;
use Facades\App\Repository\StockTake;

class inventoryController extends Controller
{

		public function HQ_invetory_list(){

			return StockTake::HQ_stockTake();
		}

		public function saveTo_hqInventoryHistory(Request $request){

			$InvHistory = new InvHistory;

			$InvHistory->web_stock_id     = $request->web_stock_id;
			$InvHistory->reference        =	$request->reference;
	    	$InvHistory->current_quantity = $request->qty;
			$InvHistory->user_id          = $request->user_id;
		    $InvHistory->created_at       = date('Y-m-d H:i:s');

		    $InvHistory->save();

		    return 'saved';

		}


}
