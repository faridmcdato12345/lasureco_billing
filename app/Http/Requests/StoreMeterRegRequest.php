<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaCodeRequest extends FormRequest
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
            'br_id' => 'required|max:255',
            'cm_id' => 'required|max:255',
            'ff_id' => 'required|max:255',
            'cons_account' => 'required|max:255',
            'mr_bill_no' => 'required|max:255',
            'mr_amount' => 'required|max:255',
            'mr_kwh_used' => 'required|max:255',
            'mr_prev_reading' => 'required|max:255',
            'mr_pres_reading' => 'required|max:255',
            'mr_date_year_month' => 'required|max:255',
            'mr_date_reg' => 'required|max:255',
            'mr_pres_dem_reading' => 'required|max:255',
            'mr_prev_dem_reading' => 'required|max:255',
            'mr_dem_kwh_used' => 'required|max:255'
        ];
    }
}
