<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Line
 * @package Models
 *
 * @method bool save()
 * @method Line insert($data)
 * @method Line get($data, $args = null,$otherArgs = [])
 * @method Line find($data, $args = null,$otherArgs = [])
 * @method Line use($plugins,...$opt)
 */
class Line extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'lines';
    protected $fillable = ['name', 'open_time', 'depart_time', 'price', 'company', 'station', 'station_back', 'last_update'];
}
