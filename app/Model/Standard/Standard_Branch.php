<?php

namespace App\Model\Standard;

use App\Helper\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Standard_Branch extends Model
{
    use SoftDeletes;
    protected $connection ='mysql3';
    protected $table = 'sm_standard_branch';


    public function stock_qty(){
        return Common::get_branchStockQty_by_ref($this->reference,$this->shop_id);
    }


}
