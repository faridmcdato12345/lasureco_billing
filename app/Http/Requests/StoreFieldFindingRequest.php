<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldFindingRequest extends FormRequest
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
            'ff_type'=>'nullable|string:max:3',
            'ff_code'=>'nullable|string:max:2',
            'ff_deesc'=>'nullable|string:max:30',
            'ff_ffinding_average'=>'nullable', //BIT
        ];
    }
}
