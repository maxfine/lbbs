<?php

namespace App\Http\Requests\Api;

class CaptchaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^1\d{10}$/',
                'unique:users'
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机格式不正确',
            'phone.unique' => '手机已经存在'
        ];
    }
}
