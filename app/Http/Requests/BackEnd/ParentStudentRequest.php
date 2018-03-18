<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ParentStudentRequest extends FormRequest
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
            'code' =>'required|digits:8',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => trans('validate.code_required'),
            'code.digits' => trans('validate.code_digits'),

        ];
    }
}
