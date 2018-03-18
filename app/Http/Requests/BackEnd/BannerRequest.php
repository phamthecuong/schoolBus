<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
                    'url' => 'required|url',
                    'banner' => 'required|mimes:jpeg,jpg,png',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'url' => 'required|url',
                    'banner' => 'mimes:jpeg,jpg,png',
                ];
            }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'url.required' => trans('validate.urL_required'),
            'url.url'   => trans('validate.url_url'),
            'banner.required' => trans('validate.banner_required'),
            'banner.mimes' => trans('validate.banner_mimes')
        ];
    }
}
