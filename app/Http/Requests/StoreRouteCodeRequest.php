<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteCodeRequest extends FormRequest
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
            'rc_desc' => 'required|max:255',
            'rc_code' => 'required|numeric|digits_between:0,4',
        ];

        if($this->getMethod() == 'post'){
            $rules += ['tc_id' => 'required'];
        }

        return $rules;
    }
}
