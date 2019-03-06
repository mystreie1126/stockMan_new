<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class updatePartnerStockResrouce extends Resource
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
            "type"   => "stock",
            "source" => "funtech",
            "responses" =>[
                      "order_id" => (string)$this->order_id,
                   "customer_id" => (string)$this->customer_id,
                "customer_group" => (string)$this->customer_group,
                    "created_at" => (string)$this->created_at
            ],

            "relationship" => [
                [
                    'name' => 'order',
                    'link' => route('order.each',['id'=>$this->order_id])
                ]
            ]

        ];
    }
}
