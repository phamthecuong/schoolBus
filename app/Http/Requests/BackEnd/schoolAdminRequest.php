<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class schoolAdminRequest extends FormRequest
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
            // $rules = [
            //         'name' => 'required',
            //     ];
            
            // if ($this->method() == 'PUT')
            // {
            //     $rules = [
            //         'email' => 'required|email|',
            //     ];

            //     if (strlen($_REQUEST['old_password']) > 0 || strlen($_REQUEST['new_password']) > 0 || strlen($_REQUEST['confirm_password']) > 0)
            //     {   
            //         $rules = array_merge_recursive($rules, [
            //             'old_password' => [
            //                 'required',
            //             ]
            //         ]);

            //         $rules = array_merge_recursive($rules, [
            //             'new_password' => [
            //                 'required',
            //                 'min:6',
            //             ]
            //         ]);

            //         $rules = array_merge_recursive($rules, [
            //             'confirm_password' => [
            //                 'required',
            //                 'same:new_password'
            //             ]
            //         ]);
                   
            //     }
            // }
            // else if($this->method() == 'POST')
            // {
            //     $rules = [
            //         'email' => 'required|email|unique:users,email',
            //     ];

            //     if (strlen($_REQUEST['password']) > 0 ||strlen($_REQUEST['confirm_password']) > 0)
            //     {   
            //         $rules = array_merge_recursive($rules, [
            //             'password' => [
            //                 'required',
            //                 'min:6',
            //             ]
            //         ]);

            //         $rules = array_merge_recursive($rules, [
            //             'confirm_password' => [
            //                 'required',
            //                 'same:password'
            //             ]
            //         ]);
                   
            //     }
            // }
           
            // return $rules;
        //dd($this->admin);
        switch($this->method())
        {
            case 'GET':
            case 'POST':
            {
                if(isset($_REQUEST['new_password']))
                {
                    return [
                        'new_password' => array('required','min:6'),
                        'confirm_password' => 'required|same:new_password',
                    ];
                }
                else
                {
                    return [
                        'name' => 'required',
                        'email' => 'required|email|unique:users,email',
                        'password' => array('required','min:6'),
                        'confirm_password' => 'required|same:password',  
                    ];
                }
            }
            case 'PUT':
            {
                return [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,'. $this->admin . ',profile_id',
                    //'old_password' => array('required','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'),
                    //'new_password' => array('required','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'),
                ];
            }
            case 'PATCH':
            default:break;
        }
        
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.name_required'),
            'email.required' => trans('validation.email_required'),
            'email.email' => trans('validation.email_email'),
            'email.unique' => trans('validation.email_unique'),
            'password.required' => trans('validate.password_required'),
            'password.min' => trans('validate.password_min'),
            'password.confirmed' => trans('validate.password_confirm'),
            'password.regex' => trans('validate.password_regex'),
            'old_password.required' => trans('validate.password_required'),
            'old_password.min' => trans('validate.password_min'),
            'old_password.confirmed' => trans('validate.password_confirm'),
            'old_password.regex' => trans('validate.password_regex'),
            'new_password.required' => trans('validate.password_required'),
            'new_password.min' => trans('validate.password_min'),
            'new_password.confirmed' => trans('validate.password_confirm'),
            'new_password.regex' => trans('validate.password_regex'),
        ];
    }
}

