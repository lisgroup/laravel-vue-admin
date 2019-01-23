<?php

namespace App\Http\Requests\Nav;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Update extends FormRequest
{
    // public function __construct(Request $request)
    // {
    //     parent::__construct();
    //     $id = $request->get('aa');
    //     $data = $request->all() ?? json_decode($request->getContent(), true);
    // }
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
            'name'=>'required|unique:navs,name,'.$request->get('id'),
            'url'=>'required',
            'sort'=>'required',
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
            'name'=>'导航名',
            'url'=>'链接地址',
            'sort'=>'排序',
        ];
    }
}
