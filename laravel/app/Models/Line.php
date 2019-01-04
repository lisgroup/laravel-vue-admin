<?php

namespace App\Models;

use App\Models\Traits\HashIdHelper;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Line
 * @package Models
 *
 * @method bool save()
 * @method Line insert($data)
 * @method Line $this where($args, $where)
 * @method Line get($data, $args = null,$otherArgs = [])
 * @method Line find($data, $args = null,$otherArgs = [])
 * @method Line use($plugins,...$opt)
 */
class Line extends Model
{
    use Searchable;
    use HashIdHelper;

    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'lines';
    protected $fillable = ['name', 'price', 'car_type', 'depart_time', 'open_time', 'total_time', 'via_road', 'company', 'station', 'station_back', 'reason', 'username', 'is_show', 'last_update'];

    /**
     * 索引的字段
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('name', 'open_time','station', 'station_back');
    }
}
