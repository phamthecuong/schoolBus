<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|min:3|max:20',
            'email' => 'required|email|min:3|max:50|unique:users,email',
            'student_code' => 'required',
            'password' => array('required','min:6','confirmed'),
            'phone_number' => 'nullable|numeric|unique:drivers,phone_number|unique:parents,phone_number',
        ];
    }

    function messages()
    {
        return [
            'name.required' => trans('validate.name_required'),
            'name.max' => trans('validate.name_max'),
            'email.required' => trans('validate.email_required'),
            'email.email' => trans('validate.email'),
            'email.max' => trans('validate.email_max'),
            'email.unique' => trans('validate.email_unique'),
            'password.required' => trans('validate.password_required'),
            'password.min' => trans('validate.password_min'),
            'password.confirmed' => trans('validate.password_confirm'),
            'password.regex' => trans('validate.password_regex'),
            'student_code.required' => trans('validate.student_code_required'),
            'student_code.digits' => trans('validate.student_code_digits'),
            'phone_number.numeric' => trans('validate.phone_numeric'),
            'phone_number.unique' => trans('validate.phone_number_exist')
        ];
    }
}
