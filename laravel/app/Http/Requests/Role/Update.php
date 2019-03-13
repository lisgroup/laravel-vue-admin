<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required|string|max:100,'.$request->get('id'),
            'permissions' => 'required',
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
            'name.string' => '名称必须是字符串',
            'permissions.required' => '权限必须选择',
            'permissions.array' => '权限选择错误',
        ];
    }
}
