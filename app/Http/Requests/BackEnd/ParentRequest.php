<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
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
        
        switch($this->method())
        {
            case 'GET':
            case 'POST':
            {
                return [
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email, '. $this->parent. ',profile_id',
                    'address' => 'required',
                    'phone' => 'required|numeric|min:11',
                    'contact_email' => 'required|email|unique:parents,contact_email, '. $this->parent. ',id',
                    'image' => 'image',
                    'password' => [
                        'required',
                        'min:6',
                        // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'
                    ],
                    'password_confirmation' => 'required|same:password',
                    'student_code' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email, '. $this->parent. ',profile_id',
                    'address' => 'required',
                    'phone' => 'required|numeric|min:11',
                    'contact_email' => 'required|email|unique:parents,contact_email, '. $this->parent. ',id',
                    'image' => 'image',
                ];
            }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'full_name.required' => trans('validate.full_name_required'),
            'email.required' => trans('validate.email_required'),
            'address.required' => trans('validate.address_required'),
            'phone.required' => trans('validate.phone_required'),
            'phone.numeric' => trans('validate.phone_numeric'),
            'phone.min' => trans('validate.phone_min_11'),
            'contact_email.required' => trans('validate.contact_email_required'),
            'contact_email.email' => trans('validate.contact_email'),
            'contact_email.unique' => trans('validate.contact_email_unique'),
            //'image.required' => trans('validate.image_required'),
            'image.image' => trans('validate.image'),
            'email.unique' => trans('validate.email_unique'),
            'email.email' => trans('validate.email'),
            'password.required' => trans('validate.password_required'),
            'password.min' => trans('validate.password_min_8'),
            'password.regex' => trans('validate.password_regex'),
            'password_confirmation.required' => trans('validate.password_confirmation_required'),
            'password_confirmation.same' => trans('validate.password_confirmation_have_to_same_new_password'),
            'student_code.required' => trans('validate.student_code_required'),
            'student_code.digits' => trans('validate.student_code_digits'),
        ];
    }
}
