<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\order_details_resource;

class view_online_order_resource extends Resource
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
            "id"            =>  (string)$this->id_order,
            "attributes"    => [
                    "reference" => $this->reference,
                "total_product" => (string)count($this->details),
                 "total_amount" => (string)$this->total_paid_tax_incl,
                    "status_id" => $this->current_state,
                "date_created"  => $this->date_add
            ],
            "relationship"  => [
                'type'    =>'product',
                'links'   => route('order.each',['id'=>$this->id_order]) 
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
