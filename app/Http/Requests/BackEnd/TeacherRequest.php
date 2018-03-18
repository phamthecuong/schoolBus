<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherRequest extends FormRequest
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
            $rules = [
                'email' => 'required|email|unique:users',
                'full_name' => 'required',
                'birthday' => 'required|date_format:j/n/Y|before:now',
                'address'  => 'required',
            ];
        }
        else if($this->method() == 'PUT')
        {   
            $teacher = Teacher::with('users')->findOrFail($this->teacher);
            $rules =  [
                'email' => 'required|email|unique:users,email,'.$teacher->users[0]->id,
                'full_name' => 'required',
                'birthday' => 'required|date_format:j/n/Y|before:now',
                'address'   => 'required',
            ];

        }
        return $rules;
    }

    public function messages()
    {
        return [
            'full_name.required' => trans('validate.full_name_required'),
            'birthday.date_format' => trans('validate.birthday_date'),
            'email.required' => trans('validate.email_required'),
            'email.email' => trans('validate.Malformed'),
            'address.required'  => trans('validate.address_required'), 
            'birthday.before'   => trans('validate.birthday_before'),
            'birthday.required' => trans('validate.birthday_required'),
            'email.unique'  => trans('validate.email_unique'),
        ];
    }
}
