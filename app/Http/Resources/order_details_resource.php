<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class order_details_resource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            "type"   => "order_item",
            "source" => "funtech",
            "id"     => (string)$this->id_order,
            "attributes" => [
                "id"        => (string)$this->product_id,
                "name"      => $this->product_name,
                "reference" => (string)$this->product_reference,
                "qty"       => (string)$this->product_quantity,
                "price"     => (string)$this->total_price_tax_incl
            ],


        ];
    }
}
