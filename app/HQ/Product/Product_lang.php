<?php

namespace App\HQ\Product;

use Illuminate\Database\Eloquent\Model;

class Product_lang extends Model
{
    protected $table = 'ps_product_lang';
    protected $primaryKey = 'id_product';

    //  public function product(){
    // 	return $this->hasOne('App\HQ\Product\Product','id_product');
    // }

    // public function stock(){
    // 	return $this->hasOne('App\HQ\Product\Product_stock','id_product');
    

}
