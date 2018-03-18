<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class ProfileRequest extends FormRequest
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
        $id = Auth::user()->id;
        $rules = [];
        if (isset($_REQUEST['email']))
        {
            $rules = [
                'email' => 'required|email|unique:users,email,'.$id
            ];
        }
       
        if (isset(Auth::user()->profile->full_name) && isset($_REQUEST['name']))
        {
           $rules =  array_merge_recursive($rules, ['name' => ['required']]);
        }

        if (isset($_REQUEST['phone_number']))
        {
            $rules =  array_merge_recursive($rules, [
                'phone_number' => [
                    'numeric',
                    'sometimes',
                    'unique:drivers,phone_number,' . Auth::user()->profile->id,
                    'unique:parents,phone_number,' . Auth::user()->profile->id,
                ]
            ]);
        }
        
        if (isset($_REQUEST['old_password']) || isset($_REQUEST['new_password']) || isset($_REQUEST['confirm_password']))
        // if (strlen($_REQUEST['old_password']) > 0 || strlen($_REQUEST['new_password']) > 0 || strlen($_REQUEST['confirm_password']) > 0)
        {   
            $rules = array_merge_recursive($rules, [
                'old_password' => [
                    'required',
                    'min:6',
                    // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
                ]
            ]);

            $rules = array_merge_recursive($rules, [
                'new_password' => [
                    'required',
                    'min:6',
                    // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
                ]
            ]);

            $rules = array_merge_recursive($rules, [
                'confirm_password' => [
                    'required',
                    'min:6',
                    // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
                    'same:new_password'
                ]
            ]);
           
        }
        return $rules;
    }
    public function messages() 
    {
        return [
            'email.required' => trans('validate.email_requried'),
            'email.unique' => trans('validate.email_unique'),
            'name.required' => trans('validate.name_requried'),
            'old_password.required' => trans('validate.old_pass_requried'),
            'new_password.required' => trans('validate.new_pass_requried'),
            'confirm_password.required' => trans('validate.confirm_pass_requried'),
            'old_password.min' => trans('validate.old_pass_min_8'),
            'new_password.min' => trans('validate.new_pass_min_8'),
            'confirm_password.min' => trans('validate.confirm_pass_min_8'),
            'old_password.regex' => trans('validate.old_password_regex'),
            'new_password.regex' => trans('validate.new_pass_regex'),
            'confirm_password.regex' => trans('validate.confirm_pass_regex'),
            'confirm_password.same' => trans('validate.confirm_pass_same'),
            'email.email' => trans('validate.email_email'),
            'phone_number.unique' => trans('validate.phone_number_exist')
        ];
    }
}
