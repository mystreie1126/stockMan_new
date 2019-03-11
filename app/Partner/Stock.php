<?php

namespace App\Partner;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'ps_stock_available';

    protected $primaryKey = 'id_product';

    public function product(){
    	return $this->hasOne('App\Partner\Product','id_product');
    }

    // public function shopName(){
    // 	return $this->hasOne('App\Partner\Shop','id_shop','id_shop');
    // }
}
