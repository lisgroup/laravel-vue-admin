<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'category_id' => 'required',
            'title' => 'required',
            'author' => 'required',
            'keywords' => 'required',
            'tag_ids' => 'required',
            'markdown' => 'required'
        ];
    }

    /**
     * 中文字段关联
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'category_id' => '栏目',
            'title' => '名称',
            'author' => '作者',
            'tag_ids' => '标签',
            'keywords' => '关键词',
            'markdown' => '文章内容'
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
            'tag_ids.required' => '请选择一个标签',
        ];
    }
}
