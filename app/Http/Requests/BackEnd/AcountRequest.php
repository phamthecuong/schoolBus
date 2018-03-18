<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AcountRequest extends FormRequest
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
        if ($this->method() == 'POST')
        {
            $rules =
            [
                'email' => 'required|email|max:255|unique:users,email',
            ];
            if (isset($_REQUEST['password']) || isset($_REQUEST['confirm_pass']))
            {   
                $rules = array_merge_recursive($rules, [
                    'password' => [
                        'required',
                        // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
                        'min:6',
                    ]
                ]);

                $rules = array_merge_recursive($rules, [
                    'confirm_password' => [
                        'required',
                        'same:password'
                    ]
                ]);
               
            }    
        }
        else if($this->method() == 'PUT')
        {
            $rules =
            [
                'email' => 'required|email|max:255|unique:users,email,'. $this->account,
            ];
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'email.required'  =>  trans('validate.email_required'),
            'email.email'  =>  trans('validate.email'),
            'email.max' => trans('validate.email_max'),
            'email.unique' => trans('validate.email_unique'),
            'password.min'  => trans('validate.password_min'),
            'password.regex' => trans('validate.password_regex'),
            'password.required'  =>  trans('validate.password_required'),
            'confirm_password.required'  =>  trans('validate.confirm_password_required'),
            'confirm_password.same' => trans('validate.confirm_password_same'),
        ];
    }
}
