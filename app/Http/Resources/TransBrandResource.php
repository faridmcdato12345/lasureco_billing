<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'transformer_brand_id' => $this->tb_id,
            'transformer_brand_code' => $this->tb_brand_code,
            'transformer_brand_desc' => $this->tb_brand
        ];
    }
}
