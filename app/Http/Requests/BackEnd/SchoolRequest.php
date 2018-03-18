<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
            'phone' => 'required|numeric|min:11',
            'name' => 'required',
            'address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => trans('validate.phone_requried'),
            'phone.numeric' => trans('validate.phone_numeric'),
            'phone.min' => trans('validate.phone_min_11'),
            'name.required' => trans('validate.name_required'),
            'address.required' => trans('validate.address_required'),

        ];
    }
}
