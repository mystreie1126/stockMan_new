<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\order_details_resource;

class all_online_order_resource extends Resource
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

        //return count($this->details);

        return [
            "type"          => "orders",
            "source"        => "funtech",
            "responses"    => [
                     "order_id" => $this->id_order,
                    "reference" => $this->reference,
                "total_product" => (string)count($this->details),
                 "total_amount" => (string)$this->total_paid_tax_incl,
                    "status_id" => $this->current_state,
                "date_created"  => $this->date_add
            ],
            "relationship"  => [
                [
                    'name'    => 'order_products',
                    'links'   => route('order_details.each',['id'=>$this->id_order]) 
                ],

        

             
            ]
           
        ];
    }

    public function with($request){
        return [
            "author"    =>"Jian",
            "version"   =>"1.0.0"
        ];
    }
}
