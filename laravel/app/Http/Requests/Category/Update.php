<?php

namespace App\Http\Requests\Category;

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
            'name'=>'required|unique:category,name,'.$request->get('id'),
            'keywords'=>'required',
            'description'=>'required',
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
            'name'=>'栏目名',
            'keywords'=>'关键字',
            'description'=>'描述',
        ];
    }
}
