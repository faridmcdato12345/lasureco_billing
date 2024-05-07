<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RightsResource extends JsonResource
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
            'right_name' => $this->right_name,
            'right_description' => $this->right_description
        ];
    }
}
