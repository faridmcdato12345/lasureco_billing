<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldFindingResource extends JsonResource
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
            'field_finding_id' => $this -> ff_id,
            'field_finding_type' => $this -> ff_type,
            'field_finding_code' => $this -> ff_code,
            'field_finding_desc' => $this -> ff_desc,
            'field_finding_average' => $this -> ff_ffinding_average
        ];
    }
}
