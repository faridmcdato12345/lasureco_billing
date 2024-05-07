<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountingCodeRequest extends FormRequest
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
            'code' => 'required|string|min:1|max:100',
            'name' => 'required|string|min:1|max:100',
            'parent_code' => 'nullable|integer|min:1|max:100',
            'a_code' => 'nullable|json',
            'main_code' => 'nullable',
        ];

        if(in_array($this->getMethod(),['PUT','PATCH'])){
            $rules['main_code'] = ['nullable','string'];
            $rules['code'] = ['required','string'];
        }

        return $rules;
    }
}
