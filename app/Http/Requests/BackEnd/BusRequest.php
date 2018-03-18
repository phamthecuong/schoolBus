<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class BusRequest extends FormRequest
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
                    'name' => 'required|unique:bus',
//                    'number' => 'required|integer|min:0',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:bus,name,'.$this->bus,
//                    'number' => 'required|integer|min:0',
                ];
            }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'name.required' => trans('validate.bus_name_required'),
            'name.unique'   => trans('validate.bus_name_unique'),
            /*'number.required'   => trans('validate.bus_number_required'),
            'number.integer'   => trans('validate.bus_number_integer'),
            'number.min'   => trans('validate.bus_number_min'),*/
        ];
    }
}
