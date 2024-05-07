<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class LifeLineRatesResource extends JsonResource
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
            'lifeline_min_kwh' => $this -> ll_min_kwh,
            'lifeline_max_kwh' => $this -> ll_max_kwh,
            'lifeline_discount' => $this -> ll_discount
        ];
    }
}
