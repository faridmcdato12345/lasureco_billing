<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FeesResource extends JsonResource
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
            'fees_id' => $this -> f_id,
            'fees_code' => $this -> f_code,
            'fees_desc' => $this -> f_description,
            'fees_amount' => $this -> f_amount,
            'fees_vatable' => $this -> f_vatable,
            'fees_percent' => $this -> f_percent
        ];
    }
}
