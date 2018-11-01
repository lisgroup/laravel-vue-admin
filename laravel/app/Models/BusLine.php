<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLine extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'bus_lines';
    protected $fillable = ['name', 'cid', 'LineGuid', 'LineInfo', 'FromTo', 'expiration'];
}
