<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
     * 获取适用于请求的验证规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,80',
            'email' => 'email|max:255',
            'password' => 'max:80',
        ];
    }

    /**
     * 中文错误提示
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '名称不能为空',
            'name.between' => '名称输入长度3-80位',
            'email.email' => '邮箱格式有误',
            'email.max' => 'cid 输入有误',
            'password.max' => '密码输入长度有误',
        ];
    }
}
