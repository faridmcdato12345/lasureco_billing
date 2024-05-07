<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EWalletResources extends JsonResource
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
            'EWallet_ID' => $this->ew_id,
            'Cons_ID' => $this->cm_id,
            'Ewallet_Total_Amount' => $this->ew_total_amount
        ];
    }
}
