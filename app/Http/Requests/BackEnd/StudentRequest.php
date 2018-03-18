<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        if ($this->method() == 'PUT') 
        {
            return [
               'full_name' => 'required',
               'code' => 'required|unique:students,code,'.$this->student,
               'birthday' => 'required|date|before:now',
               'image' => 'image:jpg,png',
               'address' => 'required',
               'departure_1' => 'required',
               'departure_2' => 'required'
            ];
        }
        else 
        {
            return [

               'full_name' => 'required',
               'code' => 'required|unique:students,code',
               'birthday' => 'required|date|before:now',
               'image' => 'required|image:jpg,png',
               'address' => 'required',
               'departure_1' => 'required',
               'departure_2' => 'required'
            ];
        }
       
    }

    public function messages()
    {
        return [
            'full_name.required' => trans('validate.full_name_requried'),
            'image.image' => trans('validate.image_malformed'),
            'image.required' => trans('validate.image_required'),
            'code.required' => trans('validate.code_required'),
            'code.unique' => trans('validate.The_code_has_already_been_taken.'),
            'birthday.date' => trans('validate.birthday_date'),
            'birthday.before' => trans('validate.birthday_before'),
            'birthday.required' => trans('validate.birthday_required'),
            'address.required' => trans('validate.address_required'),
            'departure_1.required' => trans('validate.departure_1_required'),
            'departure_2.required' => trans('validate.departure_2_required'),

        ];
    }
}
