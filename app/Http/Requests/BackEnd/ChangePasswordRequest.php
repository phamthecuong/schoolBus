<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required',
            // 'new_password' => array('required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'),
            'new_password' => array('required','min:6'),
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => trans('validate.old_password_required'),
            'new_password.required' => trans('validate.new_password_required'),
            'new_password.min'  =>  trans('validate.new_password_min'),
            'new_password.regex'    =>  trans('validate.new_password_regex'),
            'confirm_password.required' => trans('validate.confirm_password_required'),
            'confirm_password.same' => trans('validate.confirm_password_same_new_password')

        ];
    }
}
