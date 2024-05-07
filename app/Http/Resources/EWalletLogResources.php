<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EWalletLogResources extends JsonResource
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
            'EWallet_Amount' => $this->ewl_amount,
            'Ewallet_Remark' => $this->ewl_remark,
            'Ewallet_Status' => $this->ewl_status,
            'EWallet_OR' => $this->ewl_or,
            'EWallet_OR_Date' => $this->ewl_or_date,
            'EWallet_Date' => $this->ewl_date,
            'EWallet_ap_billing' => $this->ewl_ap_billing,
            'EWallet_User_ID' => $this->ewl_ap_billing_user_id,
        ];
    }
}
