<?php

namespace App\Http\Requests\ApiExcel;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'appkey'=>'required',
            'api_excel_id'=>'required',
            'upload_url'=>'required',
            'description' => 'required',
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
            'api_excel_id'=>'接口地址',
            'upload_url'=>'上传文件',
            'description'=>'描述内容',
        ];
    }
}
