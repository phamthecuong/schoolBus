<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'full_name' =>'required',
            'phone' =>  'required|min:11|numeric',
        ];
        if ($this->method() == "POST")
        {   
            $rules = array_merge_recursive($rules, [
                    'image' => 'required|image',
                ]);

            $rules = array_merge_recursive($rules, [
                    'email' => [
                        'required',
                        'email',
                        'unique:users'
                    ],
                    'password' => [
                        'required',
                        'min:6',
                        // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
                    ],
                    'confirm_password' => [
                        'required',
                        'same:password'
                    ]
                ]);
        }
        else if ($this->method() == 'PUT')
        {   
            $rules = array_merge_recursive($rules, [
                    'email' => [
                        'required',
                        'email',
                        'unique:users,email,' . $this->driver . ',profile_id',
                    ]
                ]);

             $rules = array_merge_recursive($rules, [
                    'image' => 'image'
                ]);
            // if ( strlen($_REQUEST['old_password']) > 0 || strlen($_REQUEST['new_password']) > 0 || strlen($_REQUEST['confirm_password']) > 0)
            // {   
            //     $rules = array_merge_recursive($rules, [
            //         'old_password' => [
            //             'required',
            //             'min:8',
            //         ]
            //     ]);

            //     $rules = array_merge_recursive($rules, [
            //         'new_password' => [
            //             'required',
            //             'min:8',
            //             'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
            //         ]
            //     ]);

            //     $rules = array_merge_recursive($rules, [
            //         'confirm_password' => [
            //             'required',
            //             'min:8',
            //             'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/',
            //             'same:new_password'
            //         ]
            //     ]);
               
            // }
        }
        
        return $rules;
    }

    function messages() 
    {
        return [
            'full_name.required' => trans("validate.full_name_required"),
            'phone.required' => trans("validate.phone_required"),
            'phone.min' => trans("validate.phone_min"),
            'phone.numeric' => trans("validate.phone_numeric"),
            'email.required' => trans("validate.email_required"),
            'email.email' => trans("validate.email_email"),
            'email.unique' => trans("validate.email_unique"),
            'image.required' => trans("validate.image_required"),
            'image.image' => trans("validate.image_image"),
            'password.required' => trans("validate.password_required"),
            'password.min' => trans("validate.password_min_8"),
            'password.regex' => trans("validate.password_regex"),
            'confirm_password.required' => trans("validate.confirm_password_requried"),
            'confirm_password.min' => trans("validate.confirm_password_min_8"),
            'confirm_password.same' => trans("validate.confirm_password_same"),
            'new_password.required' => trans("validate.new_password_required"),
            'new_password.min' => trans("validate.new_password_min_8"),
            'new_password.regex' => trans("validate.new_password_regex"),
        

        ];

    }
}
