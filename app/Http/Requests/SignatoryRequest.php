<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SignatoryRequest extends FormRequest
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
            'name'=> 'required|string|min:1|max:100',
            'signatory_title_id'=> 'required|unique:signatory,signatory_title_id'.$this->id
        ];
        
        if(in_array($this->getMethod(),['PUT','PATCH'])){
            $rules['name'] = ['required','string','max:100'];
            $rules['signatory_title_id'] = ['required',Rule::unique('signatory')->ignore(request()->route('signatory'))];  
            
        }
        if(in_array($this->getMethod(),['POST'])){
            
            $sti = request('signatory_title_id');
            $status = DB::table('signatory')
            ->select('status')
            ->where('signatory_title_id', $sti)
            ->where('status', 1)
            ->first();
            // dd($status);
            if ($status) {
                $rules['name'] = ['required', 'string', 'max:100'];
                $rules['signatory_title_id'] = ['required',Rule::unique('signatory')->ignore(request()->route('signatory'))];  
            } else {
                // dd(1);
                $rules1 = [
                    'name'=> 'required|string|min:1|max:100',
                    'signatory_title_id'=> 'required'
                ];
                $rules1['name'] = ['required', 'string', 'max:100'];
                $rules1['signatory_title_id'] = ['required']; 
                return $rules1;
            }
            
        }
        return $rules;
    }
}
