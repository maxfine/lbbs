<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => [
                        'required',
                        'between:3,25',
                        'regex:/^[a-zA-Z0-9\_]+$/',
                        'unique:users,name'
                    ],
                    'password' => 'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string'
                ];
            break;
            case 'PATCH':
                $uid = \Auth::guard('api')->id();
                return [
                    'name' => [
                        'required',
                        'between:3,25',
                        'regex:/^[a-zA-Z0-9\_]+$/',
                        'unique:users,name,'. $uid
                    ],
                    'email' => 'email',
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'integer|exists:images,id,type,avatar,user_id,'. $uid
                ];
            break;
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '短信验证码 key',
            'verification_code' => '短信验证码',
            'introduction' => '个人简介',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杆和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
