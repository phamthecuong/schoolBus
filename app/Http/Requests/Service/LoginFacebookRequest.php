<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class LoginFacebookRequest extends FormRequest
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
            'facebook_id' => 'required'
        ];
    }
}
