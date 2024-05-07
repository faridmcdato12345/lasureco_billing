<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreFeederCodeRequest extends FormRequest
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
            'fc_code' => 'required|string|min:1|max:10',
            'fc_desc'=>'required|string|min:1|max:255',
        ];

        if($this->getMethod() == 'post'){
            $rules += ['sc_id' => 'required|numeric'];
        }

        return $rules;
    }
}
