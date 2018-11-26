<?php

namespace App\Http\Requests\Api;

class VerificationCodeRequest extends FormRequest
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
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'captcha_key.required' => '图片验证码key不能为空',
            'captcha_key.string' => '图片验证码key必须为字符串',
            'captcha_code.required' => '图片验证码不能为空',
            'captcha_code.string' => '图片验证码必须为字符串',
        ];
    }
}
