<?php

namespace App\HQ\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';

    public function name(){
    	return $this->hasOne('App\HQ\Product\Product_lang','id_product');
    }

    public function stock(){
    	return $this->hasOne('App\HQ\Product\Product_stock','id_product');
    }

}
