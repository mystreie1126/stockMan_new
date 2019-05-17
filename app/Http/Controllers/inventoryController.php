<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Common;
use App\Inventory\hqInventory_history as InvHistory;

class inventoryController extends Controller
{

	public function HQ_invetory_list(){

		$arr = [];
		$inventory = Common::hq_inventory_list();

		foreach($inventory as $inve){
			if(Common::get_productName_by_ref($inve->ref)){
				$arr[] = [
					'web_stock_id' => $inve->stock_id,
					'reference'    => $inve->ref,
					'name'         => Common::get_productName_by_ref($inve->ref)
				];
			}
		}
		return $arr;
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
