<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiExcelLogs extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'api_excel_logs';

    protected $fillable = ['api_excel_id', 'sort_index', 'param', 'result', 'created_at'];

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
