<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoleMasterRequest extends FormRequest
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
            'pm_pole_no'=>'nullable|string|max:10',
            'pm_description'=>'nullable|string|max:50',
            'pm_location'=>'nullable|string|max:30',
            'pm_rental'=>'nullable|numeric',
            'pm_tsf_no'=>'nullable|string|max:15',
            'pm_line_section'=>'nullable|string|max:4',
            'pm_pole_type'=>'nullable|string|max:5',
            'pm_height'=> 'nullable|string|max:10',
            'pm_class'=>'nullable|string|max:15',
            'pm_ownership'=>'nullable|string|max:7',
            'pm_code'=>'nullable|string|max:15',
            'pm_type'=>'nullable|string|max:1',
            'pm_name'=>'nullable|string|max:30',
            'pm_typexxx'=>'nullable|string|max:9',
            'pm_pole'=>'nullable|string|max:1',
            'pm_configuration'=>'nullable|string|max:10',
            'pm_phasing'=>'nullable|string|max:10',
            'pm_structure'=>'nullable|string|max:10',
            'pm_account_full'=>'nullable|string|max:10',
            'pm_tapping_point'=>'nullable|string|max:30',
            'pm_phase_tapping'=>'nullable|string|max:10',
            'pm_length'=>'nullable|numeric',
            'pm_wire_size'=>'nullable|string|max:5',
            'pm_wire_type'=>'nullable|string|max:10',
            'pm_unit'=>'nullable|string|max:10',
            'pm_feederxxx'=>'nullable|string|max:2',
            'pm_cor_x'=>'nullable|numeric',
            'pm_cor_y'=>'nullable|numeric',
            'pm_cor_z'=>'nullable|numeric',
            'pm_typexxx'=>'nullable|string|max:50',
            'pm_status'=>'nullable|string|max:15',
            //'pm_userid'=>'nullable|string|max:10',
            //'pm_userdate'=>'nullable|string|max:8',
            //'pm_usertime'=>'nullable|Integer',
        ];

        if($this->getMethod() == 'post'){
            $rules += [
                'sc_id'=>'nullable',
                'fc_id'=>'nullable',
            ];
        }

        return $rules;
    }
}
