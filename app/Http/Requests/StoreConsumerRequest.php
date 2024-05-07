<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsumerRequest extends FormRequest
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
        return [
            'rc_id' => 'required',
            'ct_id' => 'required',
            'tin' => 'nullable',
            'cm_last_name' =>'bail|required|max:255',
            'cm_first_name' => 'bail|required|max:255',
            'cm_middle_name' => 'nullable|max:255',
            'cm_full_name' => 'bail|required|max:255',
            'cm_address' => 'bail|required|max:255',
            'cm_birthdate' => 'nullable|date',
            'cm_con_status' => 'nullable',
            'cm_image_url' => 'nullable',
            'employee' => 'nullable|integer',
            'temp_connect' => 'nullable|integer',
            'senior_citizen' => 'nullable|integer',
            'institutional' => 'nullable|integer',
            'metered' => 'nullable|integer',
            'govt' => 'nullable|integer',
            'main_accnt' => 'nullable|integer',
            'large_load' => 'nullable|integer',
            'nearest_cons' => 'nullable|integer',
            'occupant' => 'nullable',
            'board_res' => 'nullable',
            'cm_seq_no' => 'nullable',
            'pending' => 'nullable|integer',
            'teller_user_id' => 'nullable',
            'temp_cons_id' => 'nullable',
        ];
    }
}
