<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BillRatesResource;
use App\Http\Resources\ConsumerResource;
use App\Http\Resources\FieldFindingResource;

class MeterRegResource extends JsonResource
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
            'meter_reg_id' => $this -> mr_id,
            'bill_rates' => BillRatesResource::collection(
                DB::table('billing_rates')
                ->where('id',$this->br_id)
                ->get()
            ),
            'cons_master' => ConsumerResource::collection(
                DB::table('cons_master')
                ->where('cm_id',$this-> cm_id)
                ->get()
            ),
            'field_finding' => FieldFindingResource::collection(
                DB::table('field_finding')
                ->where('ff_id',$this->ff_id)
                ->get()
            ),
            'meter_reg_bill_no' => $this -> mr_bill_no,
            'meter_reg_amount' => $this -> mr_amount,
            'meter_reg_kwh_used' => $this -> mr_kwh_used,
            'meter_reg_previous_reading' => $this -> mr_prev_reading,
            'meter_reg_present_reading' => $this -> mr_pres_reading,
            'meter_reg_date_year_month' => $this -> mr_date_year_month,
            'meter_reg_status' => $this -> mr_status,
            'meter_reg_date_reg' => $this -> mr_date_reg,
            'meter_reg_due_date' => $this -> mr_due_date,
            'meter_reg_cancel_bill' => $this -> mr_cancel_bill,
            'meter_reg_mr_printed' => $this -> mr_printed
        ];
    }
}
