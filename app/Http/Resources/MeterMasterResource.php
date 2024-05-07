<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MeterMasterResource extends JsonResource
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
            'meter_master_id'=> $this->mm_id,
            'meter_brand'=> 
                DB::table('meter_brand')
                ->select('mb_id','mb_code','mb_name')
                ->where('mb_id',$this->mb_id)
                ->whereNull('deleted_at')
                ->get(),
            'meter_condition'=>
                DB::table('meter_cond')
                ->select('mc_id','mc_desc')
                ->where('mc_id',$this->mc_id)
                ->whereNull('deleted_at')
                ->get(),
            'meter_master_brand'=> $this->mm_brand,
            'meter_master_serial_no'=> $this->mm_serial_no,
            'meter_master_side_seal'=> $this->mm_side_seal,
            'meter_master_terminal_seal'=> $this->mm_terminal_seal,
            'meter_master_catalog_number'=> $this->mm_catalog_number,
            'meter_master_class'=> $this->mm_class,
            'meter_master_rr'=> $this->mm_rr,
            'meter_master_kh'=> $this->mm_kh,
            'meter_master_ampere'=> $this->mm_ampere,
            'meter_master_type'=> $this->mm_type,
            'meter_master_stator'=> $this->mm_stator,
            'meter_master_jaws'=> $this->mm_jaws,
            'meter_master_rs'=> $this->mm_rs,
            'meter_master_form'=> $this->mm_form,
            'meter_master_wire'=> $this->mm_wire,
            'meter_master_volts'=> $this->mm_volts,
            'meter_master_phase'=> $this->mm_phase,
            'meter_master_demand_type'=> $this->mm_demand_type,
            'meter_master_time_interval'=> $this->mm_time_interval,
            'meter_master_tsf_factor'=> $this->mm_tsf_factor,
            'meter_master_kwh_multiplier'=> $this->mm_kwh_multiplier,
            'meter_master_owner'=> $this->mm_owner,
            'meter_master_logo_seal'=> $this->mm_logo_seal,
            'meter_master_digital'=> $this->mm_digital,
            'meter_master_area'=> $this->mm_area,
            'meter_master_on_stock'=> $this->mm_on_stock,
            'meter_master_others'=> $this->mm_others,
            'meter_master_erc_seal'=> $this->mm_erc_seal,
            'meter_master_wire_type'=> $this->mm_wire_type,
            'meter_master_meter_box'=> $this->mm_meter_box,
            'meter_master_base_meter_seal'=> $this->mm_base_meter_seal,
            'meter_master_last_calib_date'=> $this->mm_last_calib_date,
            'meter_master_prev_energy_rdg'=> $this->mm_prev_energy_rdg,
            'meter_master_prev_demand_rdng'=> $this->mm_prev_demand_rdng,
            'meter_master_pt_ratio'=> $this->mm_pt_ratio,
            'meter_master_ct_ratio'=> $this->mm_ct_ratio,
            'meter_master_demand_rate'=> $this->mm_demand_rate,
            'meter_master_min_energy_kwh'=> $this->mm_min_energy_kwh,
            'meter_master_min_dem_kw'=> $this->mm_min_dem_kw,
            'meter_master_max_dem_kw'=> $this->mm_max_dem_kw,
            'meter_master_percent_rate'=> $this->mm_percent_rate,
            'meter_master_billing_determ'=> $this->mm_billing_determ,
            'meter_master_loc_pms_no'=> $this->mm_loc_pms_no,
            'meter_master_remarks'=> $this->mm_remarks,
            'meter_master_fullacct_no'=> $this->mm_fullacct_no,
            'meter_master_name'=> $this->mm_name,
            'meter_master_accuracy_perc'=> $this->mm_accuracy_perc,
            'meter_master_asfound'=> $this->mm_asfound,
            'meter_master_asleft'=> $this->mm_asleft,
            'meter_master_min_demand_kwh'=> $this->mm_min_demand_kwh,
            'meter_master_max_demand_kwh'=> $this->mm_max_demand_kwh,
            'meter_master_prev_kvarh_rdng'=> $this->mm_prev_kvarh_rdng,
            'meter_master_coopown'=> $this->mm_coopown,
            'meter_master_kw_multiplier'=> $this->mm_kw_multiplier,
            'meter_master_kvar_multiplier'=> $this->mm_kvar_multiplier,
            'meter_master_last_calib_time'=> $this->mm_last_calib_time,
            'meter_master_meter_digits'=> $this->mm_meter_digits,
            'meter_master_metering_type'=> $this->mm_metering_type,
            'meter_master_kind'=> $this->mm_kind,
            'meter_master_seal_type'=> $this->mm_seal_type,
            'meter_master_seal1'=> $this->mm_seal1,
            'meter_master_seal2'=> $this->mm_seal2,
            'meter_master_seal3'=> $this->mm_seal3,
            'meter_master_seal4'=> $this->mm_seal4,
            'meter_master_seal5'=> $this->mm_seal5,
            'meter_master_seal6'=> $this->mm_seal6,
            'meter_master_color1'=> $this->mm_color1,
            'meter_master_color2'=> $this->mm_color2,
            'meter_master_color3'=> $this->mm_color3,
            'meter_master_color4'=> $this->mm_color4,
            'meter_master_color5'=> $this->mm_color5,
            'meter_master_color6'=> $this->mm_color6,
            'meter_master_userid'=> $this->mm_userid,
            'meter_master_userdate'=> $this->mm_userdate,
            'meter_master_usertime'=> $this->mm_usertime,
            'meter_master_status'=> $this->mm_status,
            'meter_master_statusdate'=> $this->mm_statusdate,
            'meter_master_sm_no'=> $this->mm_sm_no,
            'meter_master_condition'=> $this->mm_condition
        ];
    }
}
