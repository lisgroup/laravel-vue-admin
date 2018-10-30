<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'crons';
    protected $fillable = ['line_info', 'content'];
}
