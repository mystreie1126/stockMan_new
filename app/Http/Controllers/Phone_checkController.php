<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Common;
use Excel;
use DB;

ini_set('max_execution_time', 180);
class Phone_checkController extends Controller
{   
    private function pos_parts_qty($parts_id,$shop_id){
        $pos_qty = DB::table('c1ft_pos_prestashop.ps_stock_available')
            ->where('id_shop',$shop_id)
            ->where('id_product',$parts_id)
            ->value('quantity');
        return intval($pos_qty);
    }

    public function index(){

        $shops = DB::table('c1ft_pos_prestashop.ps_shop')->whereNotIn('id_shop',[42,41,35,1])->get();

        $missmatched_shops = DB::table('c1ft_stock_manager.sm_pop_import as a')
                             ->join('c1ft_pos_prestashop.ps_shop as b','a.shop_id','b.id_shop')
                             ->groupBy('a.shop_id')->get();

        $wrongPart_shops = DB::table('c1ft_stock_manager.sm_parts_import as a')
                            ->join('c1ft_pos_prestashop.ps_shop as b','a.shop_id','b.id_shop')
                            ->groupBy('a.shop_id')->get();

        foreach($missmatched_shops as $shop){
            $shop->devices = DB::table('c1ft_stock_manager.sm_pop_import')
                             ->where('shop_id',$shop->id_shop)->get();
            $shop->count   = $shop->devices->count();

        }

        foreach($wrongPart_shops as $shop){
            $shop->parts  = DB::table('c1ft_stock_manager.sm_parts_import')
                            ->where('shop_id',$shop->id_shop)->get();
            $shop->count  = $shop->parts->count();
        }
        
        return view('phone_check.excel_import',compact('shops','missmatched_shops','wrongPart_shops'));
    }

    public function import(Request $request){
       
        $this->validate($request, [
            'shop_id'      => 'required',
            'select_file'  => 'required',
            'options'      => 'required'
        ]);

        $path = $request->file('select_file')->getRealPath();

        $sheet_data = Excel::load($path)->get();
        /*
            options 1 => devices
            options 2 => parts
        */
        if(intval($request->options) == 1)
        {   
            $keys = [];
            foreach ($sheet_data[0][4] as $key => $value) {
                array_push($keys,$key);
            }
            /*
                $keys[2] -> name
                $keys[3] -> imei
                $keys[4] -> status
            */           
            $devices = [];
            foreach($sheet_data[0] as $sheet){
                if($sheet[$keys[2]] !== null && $sheet[$keys[3]] !== null && $sheet[$keys[4]] !== null)
                array_push($devices,$sheet);
            }

            foreach($devices as $device){
                //check the in stock imei
                if(strpos(strtolower($device[$keys[4]]),'stock') !== false){

                    if(Common::checkdeviceInPos_inStock(intval($device[$keys[3]]),intval($request->shop_id)) !== 1){
                        DB::table('c1ft_stock_manager.sm_pop_import')->insert(
                            [
                                'name' => $device[$keys[2]],
                                'imei' => $device[$keys[3]],
                                'status'=>$device[$keys[4]],
                                'shop_id' => $request->shop_id,
                                'date_add' => date('Y-m-d')
                            ]
                        );
                    }
                }
            }
            
        }
        else if(intval($request->options) == 2)
        {
            $keys = [];
            foreach ($sheet_data[0] as $key => $value) {
                array_push($keys,$key);
            }

            foreach($sheet_data as $data){
                if(Common::checkPartsQty_ifMatchInPos(intval($data[$keys[0]]),intval($request->shop_id),intval($data[$keys[2]])) == 0){
                    DB::table('c1ft_stock_manager.sm_parts_import')->insert(
                        [
                            'parts_name' => $data[$keys[1]],
                            'parts_id'   => $data[$keys[0]],
                            'shop_id'    => intval($request->shop_id),
                            'sheet_stock'=> intval($data[$keys[2]]),
                            'pos_stock'  => self::pos_parts_qty(intval($data[$keys[0]]),intval($request->shop_id))
                        ]
                    );
                }
            }
        }   

        return redirect()->route('phone_check');
    }


    public function checkedAndDelete(Request $request)
    {   
        if($request->delete_id){
            DB::table('c1ft_stock_manager.sm_pop_import')->where('id',intval($request->delete_id))->delete();
        }

        return redirect()->route('phone_check');
    }

    public function checkedAndDelete_parts(Request $request)
    {
        if($request->delete_id){
            DB::table('c1ft_stock_manager.sm_parts_import')->where('id',intval($request->delete_id))->delete();
        }

        return redirect()->route('phone_check');
    }

    public function clearAll(Request $request)
    {   
        if($request->shop_id){
            DB::table('c1ft_stock_manager.sm_pop_import')->where('shop_id',intval($request->shop_id))->delete();
        }

        return redirect()->route('phone_check');
    }

    public function clearAll_parts(Request $request)
    {   
        if($request->shop_id){
            DB::table('c1ft_stock_manager.sm_parts_import')->where('shop_id',intval($request->shop_id))->delete();
        }

        return redirect()->route('phone_check');
    }

    public function parts_import(Request $request){

        $this->validate($request, [
            
            //'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('select_file')->getRealPath();

        $sheet_data = Excel::load($path)->get();

        return $sheet_data;
    }
}
