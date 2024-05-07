<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeesRequest extends FormRequest
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
            'f_code' => 'nullable|numeric',
            'f_description' => 'required|string|min:1|max:255',
            'f_amount' => 'nullable|numeric',
            'f_vatable' => 'required|boolean',
            'f_percent' => 'nullable|numeric'
        ];
    }
}
