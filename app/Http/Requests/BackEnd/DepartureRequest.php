<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class DepartureRequest extends FormRequest
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
            'txtName'   =>  'required|max:255',
            'lat'       =>  array('required','regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,16})?))$/','unique:departures,lat,'.$this->id.',id,long,' . $this->lng),
            'lng'       =>  array('required','regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,16})?))$/'),
        ];
    }
    public function messages()
    {
        return [
            'txtName.required'  =>  trans('validate.name_required'),
            'txtName.max'       =>  trans('validate.name_max'),
            'lat.required'      =>  trans('validate.lat_required'),
            'lat.regex'         =>  trans('validate.lat_regex'),
            'lng.required'      =>  trans('validate.lng_required'),
            'lng.regex'         =>  trans('validate.lng_regex'),
            'lat.unique'        =>  trans('validate.departure_exist')
        ];
    }
}
