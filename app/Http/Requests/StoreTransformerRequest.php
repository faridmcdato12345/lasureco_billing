<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransformerRequest extends FormRequest
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
            'tcf_tsf_desc' => 'nullable|string',
            'tsf_serial_no' => 'nullable|string',
            'tsf_type' => 'nullable|string|max:1',
            'tsf_kva' => 'nullable|numeric',
            'tsf_noprimbush' => 'nullable|numeric|digits_between:1,6',
            'tsf_nosecbush' => 'nullable|numeric|digits_between:1,6',
            'tsf_phasetapping' => 'nullable|string|max:10',
            'tsf_x_r_ratio' => 'nullable|numeric',
            'tsf_perc_inpedence' => 'nullable|numeric',
            'tsf_no_load_loss' => 'nullable|integer|max:11',
            'tsf_copperloss' => 'nullable|numeric',
            'tsf_coreloss' => 'nullable|numeric',
            'tsf_tl_test_result' => 'nullable|numeric',
            'tsf_pole_no' => 'nullable|string|max:10',
            'tsf_exciting_current' => 'nullable|numeric',
            'tsf_voltageprimary' => 'nullable|string|max:6',
            'tsf_voltsecondary' => 'nullable|string|max:6',
            'tsf_connection_type' => 'nullable|string|max:20',
                // 'install_date' => $this -> {validate}
                // 'install_by' => $this -> {validate}
            'tsf_location' => 'nullable|string|max:20',
            'tsf_remarks' => 'nullable|string|max:50',
            'tsf_ownership' => 'nullable|string|max:1',
            'tsf_rental_fee' => 'nullable|numeric',
                //'date_pulled_out' => $this -> {validate}
            'tsf_cor_x_tr' => 'nullable|numeric',
            'tsf_cor_y_tr' => 'nullable|numeric',
            'tsf_cor_z_tr' => 'nullable|numeric'
        ];

        if($this->getMethod() == 'post'){
            $rules += [
                'tb_id' => 'nullable|integer',
                'sc_id' => 'nullable|integer',
                'fc_id' => 'nullable|integer',
            ];
        }

        return $rules;
    }
}
