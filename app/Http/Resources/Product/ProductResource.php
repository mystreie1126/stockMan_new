<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            "type"          => "products",
            "source"        => "funtech_stock",
            "responses"    => [
                    "id_product" => (string)$this->id_product,
                     "reference" => $this->reference,
                          "name" =>$this->name->name,
                      "stock_qty"=>$this->stock->quantity,
                       "is_phone"=>stripos( $this->name->name, 'IMEI' ) !== false
            ],
            "relationship"  => [
                [
                    'name'    => 'order_details',
                    // 'links'   => route('order_details.each',['id'=>$this->id_order]) 
                ],

        

             
            ]
           
        ];
    }
}
