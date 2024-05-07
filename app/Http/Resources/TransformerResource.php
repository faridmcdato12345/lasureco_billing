<?php

namespace App\Http\Resources;

use App\Models\SubstationCodes;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TransformerResource extends JsonResource
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
            'transformer_id' => $this->trans_id,
            'tsf_type' => $this->tcf_tsf_desc,
            'serial_no' => $this->tsf_serial_no,
            'Transformer_brand' => TransBrandResource::collection(
                DB::table('trans_brand')
                ->where('tb_id',$this->tb_id)
                ->whereNull('deleted_at')
                ->get()
            ),
            'Substation_Code' =>
                DB::table('substation_code')
                ->select('sc_id','sc_desc')
                ->where('sc_id',$this-> sc_id)
                ->whereNull('deleted_at')
                ->get(),
            //'feeder_code' => FeederCodeResource
            'service_type' => $this -> tsf_type,
            'kva' => $this -> tsf_kva,
            'num_p_bush' => $this -> tsf_noprimbush,
            'num_s_bush' => $this -> tsf_nosecbush,
            'phasing_tapping' => $this -> tsf_phasetapping,
            'xr_ration' => $this -> tsf_x_r_ratio,
            'percent_impedance' => $this -> tsf_perc_inpedence,
            'no_load_loss' => $this -> tsf_no_load_loss,
            'copperloss' => $this -> tsf_copperloss,
            'coreloss' => $this -> tsf_coreloss,
            //'tltr' => $this -> tsf_tl_test_result, {validate}
            'pole_id' => $this -> tsf_pole_no,
            'ext_cur' => $this -> tsf_exciting_current,
            'primary_voltage' => $this -> tsf_voltageprimary,
            'sec_voltage' => $this -> tsf_voltsecondary,
            'connection_type' => $this -> tsf_connection_type,
            // 'install_date' => $this -> {validate}
            // 'install_by' => $this -> {validate}
            'location' => $this -> tsf_location,
            'remarks' => $this -> tsf_remarks,
            'ownership' => $this -> tsf_ownership,
            'rental_fee' => $this -> tsf_rental_fee,
            //'date_pulled_out' => $this -> {validate}
            'core_x' => $this -> tsf_cor_x_tr,
            'core_y' => $this -> tsf_cor_y_tr,
            'core_z' => $this -> tsf_cor_z_tr,
        ];
    }
}
