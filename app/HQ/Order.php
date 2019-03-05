<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order';

    public function details(){
    	return $this->hasMany('App\HQ\Order_detail','id_order');
    }
}
