<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiExcel extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'api_excel';

    protected $fillable = ['appkey', 'concurrent', 'api_param_id', 'upload_url', 'finish_url', 'description', 'uid', 'state'];
}
