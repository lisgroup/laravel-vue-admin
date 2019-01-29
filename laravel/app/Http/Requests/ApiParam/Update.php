<?php

namespace App\Http\Requests\ApiParam;

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
            'name'=>'required|unique:api_param,name,'.$request->get('id'),
            'url'=>'required',
            'param'=>'required',
        ];
    }

    /**
     * 定义字段名中文
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'=>'名称',
            'url'=>'接口地址',
            'param'=>'接口参数',
        ];
    }
}
