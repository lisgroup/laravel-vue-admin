<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|string|between:1,15',
            'keywords' => 'max:191',
            'description' => 'max:191',
            'sort' => 'numeric',
            'pid' => 'numeric',
        ];
    }

    /**
     * 中文错误提示
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '名称不能为空',
            'name.string' => '名称必须是字符串',
            'keywords.max' => '名称输入有误',
            'description.max' => '描述内容不能太长',
            'sort.numeric' => '排序必须是数字',
            'pid.numeric' => '父栏目必须是数字',
        ];
    }
}
