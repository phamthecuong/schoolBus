<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Classes;
use Illuminate\Validation\Rule;

class ClassRequest extends FormRequest
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
        $school_id = \Auth::user()->profile->school_id;
        //$class = Classes::findOrFail($this->get('id'));
        switch($this->method())
        {
            case 'GET':
            case 'POST':
            {
                return [
                    'name' => 'required|unique:classes,name,'.$this->class. ',id,school_id, '.$school_id,
                    //'name' => 'required|unique:classes,name,school_id, '.$school_id,
                    'teacher' => 'required',
                    'year' => 'required|digits:4',

                ];
            }
            case 'PUT':
            {
                return [
                    'name' => 'required|unique:classes,name,'.$this->class. ',id,school_id, '.$school_id,
                    'teacher' => 'required',
                    'year' => 'required|digits:4',
                ];
            }
            case 'PATCH':
            default:break;
        }
        
    }

    public function messages()
    {
        return [
            'name.required' => trans('validate.name_required'),
            'name.unique'   =>  trans('validate.name_exist'),
            'teacher.required' => trans('validate.teacher_required'),
            'year.required' => trans('validate.year_required'),
            'year.digits' => trans('validate.year_digits')
        ];
    }
}
