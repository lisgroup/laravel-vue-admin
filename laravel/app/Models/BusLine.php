<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BusLine extends Model
{
    use Searchable;
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'bus_lines';
    protected $fillable = ['name', 'cid', 'LineGuid', 'LineInfo', 'FromTo', 'expiration'];

    /**
     * 索引的字段
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('id', 'name', 'LineInfo', 'FromTo');
    }
}
