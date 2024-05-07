<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeterMasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'mm_brand'=>'nullable|string:max:15',
            'mm_serial_no'=>'nullable|string:max:255',
            'mm_side_seal'=>'nullable|string:max:10',
            'mm_terminal_seal'=>'nullable|string:max:10',
            'mm_catalog_number'=>'nullable|string:max:12',
            'mm_class'=>'nullable|string:max:12',
            'mm_rr'=>'nullable|string:max:8',
            'mm_kh'=>'nullable|numeric',
            'mm_ampere'=>'nullable|string:max:10',
            'mm_type'=>'nullable|string:max:10',
            'mm_stator'=>'nullable|string:max:3',
            'mm_jaws'=>'nullable|integer',
            'mm_rs'=>'nullable|integer',
            'mm_form'=>'nullable|string|max:4',
            'mm_wire'=>'nullable|string|max:4',
            'mm_volts'=>'nullable|integer',
            'mm_phase'=>'nullable|integer',
            'mm_demand_type'=>'nullable|string|max:10',
            'mm_time_interval'=>'nullable|string|max:10',
            'mm_tsf_factor'=>'nullable|string|max:10',
            'mm_kwh_multiplier'=>'nullable|numeric',
            'mm_owner'=>'nullable|string|max:10',
            'mm_logo_seal'=>'nullable|string:max:10',
            'mm_digital'=>'nullable', //BIT //Not working
            'mm_area'=>'nullable|string|max:2',
            'mm_on_stock'=>'nullable', //BIT //Not working
            'mm_others'=>'nullable|string|max:10',
            'mm_erc_seal'=>'nullable|string|max:10',
            'mm_wire_type'=>'nullable|string|max:10',
            'mm_meter_box'=>'nullable|string|max:15',
            'mm_base_meter_seal'=>'nullable|string|max:15',
            'mm_last_calib_date'=>'nullable|string|max:8',
            'mm_prev_energy_rdg'=>'nullable|numeric',
            'mm_prev_demand_rdng'=>'nullable|numeric',
            'mm_pt_ratio'=>'nullable|string|max:10',
            'mm_ct_ratio'=>'nullable|string|max:10',
            'mm_demand_rate'=>'nullable|numeric',
            'mm_min_energy_kwh'=>'nullable|numeric',
            'mm_min_dem_kw'=>'nullable|numeric',
            'mm_max_dem_kw'=>'nullable|numeric',
            'mm_percent_rate'=>'nullable|numeric',
            'mm_billing_determ'=>'nullable|numeric',
            'mm_loc_pms_no'=>'nullable|string|max:15',
            'mm_remarks'=>'nullable|string|max:50',
            'mm_fullacct_no'=>'nullable|string|max:15',
            'mm_name'=>'nullable|string|max:50',
            'mm_accuracy_perc'=>'nullable|numeric',
            'mm_asfound'=>'nullable|numeric',
            'mm_asleft'=>'nullable|numeric',
            'mm_min_demand_kwh'=>'nullable|numeric',
            'mm_max_demand_kwh'=>'nullable|numeric',
            'mm_prev_kvarh_rdng'=>'nullable|numeric',
            'mm_coopown'=>'nullable|numeric', //BIT //Not working
            'mm_kw_multiplier'=>'nullable|numeric',
            'mm_kvar_multiplier'=>'nullable|numeric',
            'mm_last_calib_time'=>'nullable|integer|digits_between:1,11',
            'mm_meter_digits'=>'nullable|integer|digits_between:1,6',
            'mm_metering_type'=>'nullable|string|max:25',
            'mm_kind'=>'nullable|string|max:20',
            'mm_seal_type'=>'nullable|string|max:10',
            'mm_seal1'=>'nullable|string|max:14',
            'mm_seal2'=>'nullable|string|max:14',
            'mm_seal3'=>'nullable|string|max:14',
            'mm_seal4'=>'nullable|string|max:14',
            'mm_seal5'=>'nullable|string|max:14',
            'mm_seal6'=>'nullable|string|max:14',
            'mm_color1'=>'nullable|string|max:14',
            'mm_color2'=>'nullable|string|max:14',
            'mm_color3'=>'nullable|string|max:14',
            'mm_color4'=>'nullable|string|max:14',
            'mm_color5'=>'nullable|string|max:14',
            'mm_color6'=>'nullable|string|max:14',
            'mm_userid'=>'nullable|string|max:14',
            //'mm_userdate'=>'',
            //'mm_usertime'=>'',
            'mm_status'=>'nullable|string|max:1',
            'mm_statusdate'=>'nullable|string|max:8', //date
            'mm_sm_no'=>'nullable|string|max:8',
            'mm_condition'=>'nullable|integer', //Small int(6) not woring for digits_between 6
        ];

        if($this->getMethod() == 'post'){
            $rules += [
                'mb_id'=>'nullable', //fk
                'mc_id'=>'nullable', //fk,
            ];
        }
        
        return $rules;
    }
}
