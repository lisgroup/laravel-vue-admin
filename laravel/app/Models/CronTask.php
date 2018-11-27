<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronTask extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'cron_task';
    protected $fillable = ['cid', 'LineGuid', 'LineInfo', 'is_task', 'start_at', 'end_at'];
}
