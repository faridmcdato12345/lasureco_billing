<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MeterBrandResource extends JsonResource
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
            'meter_brand_id' => $this -> mb_id,
            'meter_brand_code' => $this -> mb_code,
            'meter_brand_name' => $this -> mb_name
        ];
    }
}
