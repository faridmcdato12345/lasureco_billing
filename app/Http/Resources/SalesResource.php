<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SalesResource extends JsonResource
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
            'sales_id' => $this -> s_id,
            'Meter_reg' =>
                DB::table('meter_reg')
                ->select('mr_bill_no')
                ->where('mr_id',$this-> mr_id)
                ->whereNull('deleted_at')
                ->get(),
            'fee_codes' =>
                DB::table('fees')
                ->select('f_id')
                ->where('f_id',$this-> f_id)
                ->whereNull('deleted_at')
                ->get(),
            'consumer_type' => 
                DB::table('cons_type')
                ->select('ct_id')
                ->where('ct_id', $this-> ct_id)
                ->whereNull('deleted_at')
                ->get(),
            'consumers' =>
                DB::table('cons_master')
                ->select('cm_id')
                ->where('cm_id',$this-> cm_id)
                ->whereNull('deleted_at')
                ->get(),
            'sales_or_number' => $this -> s_or_num,
            'sales_bill_number' => $this -> s_bill_no,
            'sales_bill_amount' => $this -> s_bill_amount,
            'sales_bill_date' => $this -> s_bill_date,
            'sales_status' => $this -> s_status,
            'sales_mode_of_payment' => $this -> s_mode_payment,
            'sales_referrence_no' => $this -> s_ref_no,
            'sales_referrence_date' => $this -> ref_date,
            'sales_teller_userid' => $this -> teller_user_id,
            'sales_acknowledgement_receipt' => $this -> s_ack_receipt,
            'sales_acknowledgement_date' => $this -> ackn_date,
            'sales_acknowledgement_userid' => $this -> ackn_user_id,
            'sales_arrear' => $this -> mr_arrear,
            'sales_unposted' => $this -> s_unposted,
            'sales_ewallet_applied' => $this -> e_wallet_applied,
            'sales_ewallet_added' => $this -> e_wallet_added
        ];
    }
}
