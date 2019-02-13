<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiParam extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'api_param';

    protected $fillable = ['website', 'name', 'method', 'url', 'param', 'result', 'is_need', 'state'];

    /**
     * 一对多关联 api_excel
     */
    public function apiExcel()
    {
        return $this->hasMany(ApiExcel::class);
    }
}
