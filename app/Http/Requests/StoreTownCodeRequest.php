<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreTownCodeRequest extends FormRequest
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
        $rules = ['tc_name' => 'required|max:255'];

        if($this->getMethod() == 'post'){
            $rules += ['ac_id' => 'required|exists:area_code,ac_id'];
        }

        return $rules;
    }
}
