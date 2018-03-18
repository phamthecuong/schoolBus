<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'title' =>  'required',
            'message'   =>  'required',
            'school'    =>  'required',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required'    =>  trans('validate.title_required'),
            'message.required'  =>  trans('validate.message_required'),
            'school.required'  =>  trans('validate.school_required'),
        ];
    }
}
