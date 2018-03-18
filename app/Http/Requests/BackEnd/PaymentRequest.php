<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'class' => 'required|min:1',
            'student' => 'required|min:1',
            'description' => 'required',
            'amount' => 'required|min:1|numeric',
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|digits:4',
        ];
    }
    
    public function messages()
    {
        return [
            'class.required'    =>  trans('class.required'),
            'student.required'  => trans('student.required'),
            'description.required'  =>  trans('validation.description_required'),
            'amount.required'     =>  trans('validation.amount_required'),
            'amount.min'    => trans('validation.amount_min'),
            'amount.numeric' => trans('validation.amount_numeric'),
            'month.required'      =>  trans('validation.month_required'),
            'year.required'      =>  trans('validation.year_required'),
            'month.integer'     => trans('validation.month_int'),
            'month.between'     => trans('validation.month_between'),
            'year.integer'      => trans('validation.year_int'),
            'year.digits'       => trans('validation.year_digits'),

        ];
    }
}
