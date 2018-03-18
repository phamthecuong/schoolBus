<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ClassTransferRequest extends FormRequest
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
            'old_year'  => 'required',
            'old_class' => 'required',
            'new_year'  => 'required',
            'new_class' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'old_year.required'  =>  trans('validate.old_year_required'),
            'old_class.required'       =>  trans('validate.old_class_required'),
            'new_year.required'      =>  trans('validate.new_year_required'),
            'new_class.required'         =>  trans('validate.new_class_required'),
        ];
    }
}
