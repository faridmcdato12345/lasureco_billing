<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountingCodeResource extends JsonResource
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
            'acc_id'=>$this->id,
            'acc_code'=> $this->code,
            'acc_name'=> $this->name,
            'a_code' => $this->a_code,
            'g_code' => $this->g_code,
            'acc_parent_code'=> $this->parent_code,
            'acc_main_code'=> $this->main_code,
            'acc_is_last'=>$this->is_last,
            'acc_created_at'=>$this->created_at,
            'acc_updated_at'=>$this->updated_at,
            'acc_deleted_at'=>$this->deleted_at,
        ];
    }
}
