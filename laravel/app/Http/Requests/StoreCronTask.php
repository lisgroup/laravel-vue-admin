<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCronTask extends FormRequest
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
            'LineInfo' => 'required|string|between:2,80',
            'cid' => 'required|max:255',
            'LineGuid' => 'required|max:255',
            'is_task' => 'required',
            'account' => [
                'sometimes',
                'regex:/^1[3-9][0-9]\d{4,8}|(\w)+(\.\w+)*@(\w)+((\.\w+)+)|[0-9a-zA-Z_]+$/' // 验证账号可以为 手机号，邮箱或字符串
            ]
        ];
    }

    public function messages()
    {
        return [
            'LineInfo.required' => '线路名称不能为空',
            'LineInfo.string' => '线路名称必须是字符串',
            'LineInfo.between' => '线路名称输入有误',
            'cid.required' => 'cid 不能为空',
            'cid.max' => 'cid 输入有误',
            'LineGuid.required' => 'LineGuid 不能为空',
            'LineGuid.max' => 'LineGuid 输入有误',
        ];
    }
}
