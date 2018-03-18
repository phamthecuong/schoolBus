<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ParentPasswordRequest extends FormRequest
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
        $rules = array();
       
        $rules = array_merge_recursive($rules, [
            'old_password' => [
                'required',
                'min:6' 
            ]
        ]);

        $rules = array_merge_recursive($rules, [
            'new_password' => [
                'required',
                'min:6',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'
            ]
        ]);

        $rules = array_merge_recursive($rules, [
            'confirm_password' => [
                'required',
                'same:new_password'
            ]
        ]);
       return $rules;
    }

    public function messages()
    {
        return [
            'old_password.required' => trans('validate.old_password_required'),
            'new_password.required' => trans('validate.new_password_required'),
            'new_password.min' => trans('validate.new_password_min_8'),
            'new_password.regex' => trans('validate.new_password_regex'),
            'confirm_password.required' => trans('validate.confirm_password_required'),
            'confirm_password.same' => trans('validate.confirm_password_have_to_same_new_password')

        ];
    }
}
