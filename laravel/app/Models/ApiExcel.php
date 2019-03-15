<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiExcel extends Model
{
    use SoftDeletes;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'api_excel';

    protected $fillable = ['appkey', 'concurrent', 'api_param_id', 'upload_url', 'finish_url', 'description', 'auto_delete', 'uid', 'state'];

    protected $hidden = [
        'appkey',
    ];

    protected $datas = ['deleted_at'];

    /**
     * 获取关联关系
     *
     * @return mixed
     */
    public function apiParam()
    {
        return $this->belongsTo(ApiParam::class, 'api_param_id');
    }
}
