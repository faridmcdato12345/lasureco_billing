<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubstationCodeRequest extends FormRequest
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
            'sc_desc' => 'required|string|min:1|max:255',
            'sc_address' => 'required|string|min:1|max:255',
            'sc_rating' => 'nullable|numeric',
            'sc_voltprim' => 'nullable|numeric',
            'sc_voltsecond' => 'nullable|numeric',
            'sc_xr_ratio' => 'nullable|numeric',
            'sc_exciting_curr' => 'nullable|numeric',
            'sc_impedence' => 'nullable|numeric',
            'sc_coreloss' => 'nullable|numeric',
            'sc_copperloss' => 'nullable|numeric',
            'sc_noloadloss' => 'nullable|numeric',
            'sc_con_type' => 'nullable|numeric'
        ];
    }
}
