<?php

namespace App\HQ;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{   
    public $timestamps = false;
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order'; 

    protected $fillable = ['current_state'];

    public function details(){
    	return $this->hasMany('App\HQ\Order_detail','id_order');
    }

    public function buyer_group(){
    	return $this->hasOne('App\HQ\Customer_group','id_customer');
    }

    public function buyer(){
    	return $this->belongsTo('App\HQ\Customer','id_customer','id_customer');
    }
}
