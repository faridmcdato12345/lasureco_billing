<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PoleMasterResource extends JsonResource
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
            'pole_master_id' => $this -> pm_id,
            'pole_master_pole_no' => $this ->pm_pole_no,
            'sc_id' => 
                DB::table('substation_code')
                ->select('sc_id')
                ->where('sc_id',$this->sc_id)
                ->whereNull('deleted_at')
                ->get(), 
            'fc_id' =>
                DB::table('feeder_code')
                ->select('fc_id')
                ->where('fc_id',$this->fc_id)
                ->whereNull('deleted_at')
                ->get(),
            'pole_master_description' => $this ->pm_description,
            'pole_master_location' => $this ->pm_location,
            'pole_master_rental' => $this ->pm_rental,
            'pole_master_tsf_no' => $this ->pm_tsf_no,
            'pole_master_line_section' => $this ->pm_line_section,
            'pole_master_pole_type' => $this ->pm_pole_type,
            'pole_master_height' => $this ->pm_height,
            'pole_master_class' => $this ->pm_class,
            'pole_master_ownership' => $this ->pm_ownership,
            'pole_master_code' => $this ->pm_code,
            'pole_master_type' => $this ->pm_type,
            'pole_master_name' => $this ->pm_name,
            'pole_master_typexxx' => $this ->pm_typexxx,
            'pole_master_pole' => $this ->pm_pole,
            'pole_master_configuration' => $this ->pm_configuration,
            'pole_master_phasing' => $this ->pm_phasing,
            'pole_master_structure' => $this ->pm_structure,
            'pole_master_account_full' => $this ->pm_account_full,
            'pole_master_tapping_point' => $this ->pm_tapping_point,
            'pole_master_phase_tapping' => $this ->pm_phase_tapping,
            'pole_master_length' => $this ->pm_length,
            'pole_master_wire_size' => $this ->pm_wire_size,
            'pole_master_wire_type' => $this ->pm_wire_type,
            'pole_master_unit' => $this ->pm_unit,
            'pole_master_feederxxx' => $this ->pm_feederxxx,
            'pole_master_cor_x' => $this ->pm_cor_x,
            'pole_master_cor_y' => $this ->pm_cor_y,
            'pole_master_cor_z' => $this ->pm_cor_z,
            'pole_master_typexxx' => $this ->pm_typexxx,
            'pole_master_status' => $this ->pm_status,
            'pole_master_userid' => $this ->pm_userid,
            'pole_master_userdate' => $this ->pm_userdate,
            'pole_master_usertime' => $this ->pm_usertime
        ];
    }
}
