<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLineRequest extends FormRequest
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
            'name' => 'required|string|between:2,80',
            'price' => 'required|max:255',
            'car_type' => 'required',
            'depart_time' => 'required',
            'open_time' => 'required',
            'total_time' => 'required',
            'via_road' => 'required',
            'company' => 'required',
            'station' => 'required',
            'station_back' => 'required',
            'reason' => 'required',
            'is_show' => 'required',
            'last_update' => 'required',
        ];
    }

    /**
     * 中文错误提示
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '线路名称不能为空',
            'name.string' => '线路名称必须是字符串',
            'name.between' => '线路名称输入有误',
            'price.required' => 'price 不能为空',
            'price.max' => 'price 输入有误',
            'car_type.required' => "车型需是：'大巴','中巴','地铁'",
            'depart_time.required' => '发车间隔不能为空',
            'open_time.required' => '营运时间不能为空',
            'company.required' => '公交公司不能为空',
            'station.required' => '途经站点(去程)不能为空',
            'station_back.required' => '途经站点(返程)不能为空',
            'reason.required' => '编辑理由不能为空',
            'is_show.required' => '是否审核不能为空',
            'last_update.required' => '最后更新日期不能为空',
            'account.regex' => 'account 输入有误',
        ];
    }
}
