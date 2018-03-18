<?php

namespace App\Http\Requests\BackEnd;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
                    'title' => 'required',
                    'short_description' => 'required',
                    'description' => 'required',
                    'image' => 'required|image',
                ];
            }
            case 'PUT':
            {
                return [
                    'title' => 'required',
                    'short_description' => 'required',
                    'description' => 'required',
                    'image' => 'image',
                ];
            }
            case 'PATCH':
            default:break;
        }
    }

    public function messages()
    {
        return [
            'title.required'  =>  trans('news.title_required'),
            'short_description.required'  =>  trans('news.short_des_required'),
            'image.image'     =>  trans('news.image'),
            'description.required'      =>  trans('news.des_required'),
            'image.required'      =>  trans('news.img_required'),
        ];
    }
}
