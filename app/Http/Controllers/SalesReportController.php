<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helper\Common;

class SalesReportController extends Controller
{   
   

    private function shop_total_wholesale($from,$to,$shop_id)
    {   
        if(intval($shop_id) !== 43){
            $total_price = DB::select(
                "select sum(IFNULL(round(b.wholesale,2),0) * a.updated_quantity) as price
                    from c1ft_stock_manager.sm_all_replishment_history as a
                    left join c1ft_stock_manager.sm_wholesale as b on a.reference = b.reference
                    where a.shop_id = '$shop_id' and a.created_at >= '$from' and a.created_at <= '$to'
                "
            );
            return floatval($total_price[0]->price);   

        }else if(intval($shop_id) == 43){
            $total_price = DB::select(
                "select sum(round(total_paid_tax_excl,2)) as price
                    from ps_orders 
                    where id_shop = 11
                    and id_customer = 5143
                    and date_add >= '$from'
                    and date_add <= '$to'
                "
            );
            return floatval($total_price[0]->price);
        }else {
            return 0;
        }
        
    }

    

    private function shop_total_retail($from,$to,$shop_id)
    {   
        $total_retail = DB::select(
            "select sum(round((total_paid_tax_incl - (total_paid_tax_incl * 0.23)),2)) as price
                from c1ft_pos_prestashop.ps_orders 
                where id_shop = '$shop_id'
                and current_state = 5
                and date_add >= '$from'
                and date_add <= '$to'
            "
        );

        return floatval($total_retail[0]->price);

    }

    public function shopsales(Request $request)
    {   
        
        $shops  = DB::table('c1ft_pos_prestashop.ps_shop')
                ->whereNotIn('id_shop',[1,32,35,41,42])->get();
       
        $from   = date('Y-m-d 00:00:00', strtotime('-'.intval($request->date).'days'));
        $to     = date('Y-m-d 23:50:00');

        foreach($shops as $shop){
            $shop->wholesale = self::shop_total_wholesale($from,$to,$shop->id_shop);
            $shop->retail    = self::shop_total_retail($from,$to,$shop->id_shop);
            $shop->profit    = self::shop_total_retail($from,$to,$shop->id_shop) - self::shop_total_wholesale($from,$to,$shop->id_shop);
           
        }
        
        return response()->json(['shop_data'=>$shops]);
    }


    
}
