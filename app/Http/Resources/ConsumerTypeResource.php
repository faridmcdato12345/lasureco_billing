<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsumerTypeResource extends JsonResource
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
            'cons_type_id' => $this->ct_id,
            'cons_type_code' => $this->ct_code,
            'cons_type_desc' => $this->ct_desc
        ];
    }
}
