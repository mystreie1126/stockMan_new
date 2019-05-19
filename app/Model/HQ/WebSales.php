<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class WebSales extends Model
{
    protected $table = 'vr_confirm_payment';
    protected $primaryKey = 'order_id';
    
    public function detail(){
      return $this->hasMany('App\HQ\Order_detail','id_order','order_id');
    }
}
