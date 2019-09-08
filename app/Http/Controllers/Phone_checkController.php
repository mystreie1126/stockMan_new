<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use Excel;
use DB;

ini_set('max_execution_time', 180);
class Phone_checkController extends Controller
{
    public function index(){

        $shops = DB::table('c1ft_pos_prestashop.ps_shop')->whereNotIn('id_shop',[42,41,35,1])->get();

        $missmatched_shops = DB::table('c1ft_stock_manager.sm_pop_import as a')
                             ->join('c1ft_pos_prestashop.ps_shop as b','a.shop_id','b.id_shop')
                             ->groupBy('a.shop_id')->get();

        foreach($missmatched_shops as $shop){
            $shop->devices = DB::table('c1ft_stock_manager.sm_pop_import')
                             ->where('shop_id',$shop->id_shop)->get();
            $shop->count   = $shop->devices->count();

        }


        return view('phone_check.excel_import',compact('shops','missmatched_shops'));
    }




    public function import(Request $request){

        $this->validate($request, [
            'shop_id' => 'required',
            //'select_file'  => 'required|mimes:xls,xlsx'
        ]);

          $path = $request->file('select_file')->getRealPath();

          $sheet_data = Excel::load($path)->get();

          $keys = [];
          foreach ($sheet_data[0] as $key => $value) {
              array_push($keys,$key);
           }

          /*
              $keys[2] -> name
              $keys[3] -> imei
              $keys[4] -> status
          */

          $devices = [];
          foreach($sheet_data as $sheet){
              if($sheet[$keys[2]] !== null && $sheet[$keys[3]] !== null && $sheet[$keys[4]] !== null)
              array_push($devices,$sheet);
          }

         // return $devices;

         // return Common::checkdeviceInPos_inStock((string)$devices[3][$keys[3]],$request->shop_id);

          foreach($devices as $device){
              //check the in stock imei
              if(strpos(strtolower($device[$keys[4]]),'stock') !== false){
                  if(Common::checkdeviceInPos_inStock((string)$device[$keys[3]],$request->shop_id) !== 1){
                      DB::table('c1ft_stock_manager.sm_pop_import')->insert(
                            [
                                'name' => $device[$keys[2]],
                                'imei' => $device[$keys[3]],
                                'status'=>$device[$keys[4]],
                                'shop_id' => $request->shop_id,
                                'date_add' => date('Y-m-d')
                            ]
                        );
                      //array_push($testArr,$device[$keys[3]]);

                  }
               }

          }
           //return $testArr;
          //return DB::table('c1ft_stock_manager.sm_pop_import')->get();
          // return back()->with('success', 'Excel Data Imported successfully.');
          return redirect()->route('phone_check',compact('instock_check'));

    }
}
