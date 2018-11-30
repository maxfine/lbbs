<?php

namespace App\Http\Requests\Api;

class TopicRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()) {
            case 'POST' :
                return [
                    'title' => 'required|string',
                    'body' => 'required|string',
                    'category_id' => 'required|integer|exists:categories,id',
                ];
            break;

            case 'PATCH' :
                return [
                    'title' => 'required|string',
                    'body' => 'required|string',
                    'category_id' => 'required|integer|exists:categories,id',
                ];
            break;
        }
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '内容',
            'category_id' => '栏目'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'body.required' => '内容不能为空',
            'category_id.required' => '栏目不能为空',
            'category_id.exists' => '栏目不存在'
        ];
    }
}
