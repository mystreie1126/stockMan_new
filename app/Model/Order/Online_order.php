<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Online_order extends Model
{
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order';

    public function detail(){
        return $this->hasMany('App\Model\Order\Online_order_details','id_order');
    }

    public function message(){
        return $this->hasMany('App\Model\Order\Online_order_message','id_order');
    }

    public function customer_detail(){
        return $this->hasOne('App\Model\Order\Online_pos_partner','ps_customer_id','id_customer');
    }
}
