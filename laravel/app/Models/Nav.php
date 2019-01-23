<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    // protected $table = 'navs';

    protected $fillable = ['name', 'url', 'sort'];

    /**
     * 一对多关联文章
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
