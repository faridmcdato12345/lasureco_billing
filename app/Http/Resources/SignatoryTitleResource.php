<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SignatoryTitleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'sig_title_name' => $this->name,
            'sig_title_created_at'=>$this->created_at,
            'sig_title_updated_at'=>$this->updated_at,
            'sig_title_deleted_at'=>$this->deleted_at,
        ];
    }
}
