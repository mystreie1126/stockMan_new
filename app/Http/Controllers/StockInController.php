<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use Facades\App\Repository\StockTake;
use App\Model\Record\Stock_in_history as StockInRecord;
use App\Model\Warehouse\Stock as Warehouse_Stock;
use DB;


class StockInController extends Controller
{
    public function available_stock(){
        return StockTake::stock_in_lists();
    }

    public function save_and_update(Request $request){

        $stockIn = new StockInRecord;
        $stockIn->Reference   = $request->reference;
        $stockIn->name        = $request->name;
        $stockIn->quantity    = $request->stockIn_qty;
        $stockIn->staff_id    = $request->user_id;
        $stockIn->created_at  = date('Y-m-d h:i:s');
        if($stockIn->save()){
            DB::table('ps_stock_available')->where('id_stock_available',$request->web_stock_id)
            ->increment('quantity',$request->stockIn_qty);
        }
        
        return $request->stockIn_qty;
    }
}
